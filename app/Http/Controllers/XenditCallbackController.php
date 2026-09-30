<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class XenditCallbackController extends Controller
{
    public function handleCallback(Request $request)
    {
        $expectedToken = (string) config('services.xendit.callback_token');
        $callbackToken = (string) $request->header('x-callback-token', '');

        if ($expectedToken === '' || $callbackToken === '' || !hash_equals($expectedToken, $callbackToken)) {
            Log::warning('Callback Xendit ditolak: token tidak valid.');
            return response()->json(['message' => 'Token tidak valid'], 403);
        }

        // Legacy Invoice API mengirim field di root. Fallback data.* membuat
        // endpoint tetap aman bila dashboard tidak sengaja diarahkan ke webhook Payments.
        $externalId = $this->firstScalar([
            $request->input('external_id'),
            $request->input('data.reference_id'),
        ]);

        $status = strtoupper($this->firstScalar([
            $request->input('status'),
            $request->input('data.status'),
        ]));

        $event = strtolower($this->firstScalar([$request->input('event')]));
        if ($status === '' && in_array($event, ['payment.capture', 'payment.succeeded'], true)) {
            $status = 'PAID';
        }
        if ($status === 'SUCCEEDED' || $status === 'COMPLETED' || $status === 'CAPTURED') {
            $status = 'PAID';
        }

        $jumlahDibayar = $this->moneyValue(
            $request->input('paid_amount',
                $request->input('amount',
                    $request->input('data.amount', 0)
                )
            )
        );

        $paymentMethod = $this->firstScalar([
            $request->input('payment_channel'),
            $request->input('payment_method'),
            $request->input('data.channel_code'),
            $request->input('data.type'),
        ]);
        $paymentMethod = Str::limit($paymentMethod !== '' ? $paymentMethod : 'Xendit', 50, '');

        if ($externalId === '' || !in_array($status, ['PAID', 'SETTLED', 'EXPIRED'], true)) {
            Log::warning('Payload callback Xendit tidak dikenali.', [
                'external_id' => $externalId,
                'status' => $status,
                'event' => $event,
                'payload' => $request->all(),
            ]);

            return response()->json(['message' => 'Payload callback tidak valid'], 422);
        }

        $isPelunasan = preg_match('/-LUNAS-\d{6}$/', $externalId) === 1;
        $transactionId = preg_replace('/-LUNAS-\d{6}$/', '', $externalId);

        try {
            $result = DB::transaction(function () use (
                $transactionId,
                $externalId,
                $status,
                $jumlahDibayar,
                $paymentMethod,
                $isPelunasan
            ) {
                $order = DB::table('transaksi')
                    ->where('id_transaksi', $transactionId)
                    ->lockForUpdate()
                    ->first();

                if (!$order) {
                    Log::error('Callback Xendit: transaksi tidak ditemukan.', [
                        'external_id' => $externalId,
                        'id_transaksi' => $transactionId,
                    ]);
                    return 'not_found';
                }

                // Invoice expired hanya boleh menggagalkan invoice DP yang belum dibayar.
                // Invoice pelunasan yang expired tidak boleh membatalkan DP yang sudah sah.
                if ($status === 'EXPIRED') {
                    if (!$isPelunasan && strtolower((string) $order->status) === 'menunggu') {
                        DB::table('transaksi')
                            ->where('id_transaksi', $transactionId)
                            ->update([
                                'status' => 'gagal',
                                'updated_at' => now(),
                            ]);
                    }
                    return 'expired';
                }

                if ($isPelunasan) {
                    // Retry callback pelunasan tidak boleh menambah total/stok dua kali.
                    if (strtolower((string) $order->status) === 'selesai') {
                        return 'duplicate';
                    }

                    // Potong stok hanya untuk qty tambahan yang belum pernah diproses.
                    $this->processUnreservedStock($transactionId);

                    // Grand total dihitung dari database agar tidak bergantung pada urutan retry
                    // callback DP/pelunasan dan menghindari penjumlahan berulang.
                    $grandTotal = (float) DB::table('detail_pesanan')
                        ->join('menu', 'menu.id_menu', '=', 'detail_pesanan.id_menu')
                        ->where('detail_pesanan.id_transaksi', $transactionId)
                        ->selectRaw('COALESCE(SUM(detail_pesanan.qty * menu.harga), 0) AS total')
                        ->value('total');

                    DB::table('transaksi')
                        ->where('id_transaksi', $transactionId)
                        ->update([
                            'status' => 'selesai',
                            'total' => $grandTotal > 0 ? $grandTotal : ((float) $order->total + $jumlahDibayar),
                            'metode_pembayaran' => $paymentMethod,
                            'updated_at' => now(),
                        ]);

                    return 'settlement_paid';
                }

                // Callback DP: hanya diproses saat status masih menunggu.
                if (strtolower((string) $order->status) !== 'menunggu') {
                    return 'duplicate';
                }

                $this->processUnreservedStock($transactionId);

                DB::table('transaksi')
                    ->where('id_transaksi', $transactionId)
                    ->update([
                        'status' => 'tervalidasi',
                        'total' => $jumlahDibayar,
                        'metode_pembayaran' => $paymentMethod,
                        'updated_at' => now(),
                    ]);

                return 'dp_paid';
            }, 3);

            if ($result === 'not_found') {
                // 404 sengaja dipakai agar konfigurasi salah terlihat jelas dan Xendit dapat retry.
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            Log::info('Callback Xendit berhasil diproses.', [
                'external_id' => $externalId,
                'id_transaksi' => $transactionId,
                'status' => $status,
                'pelunasan' => $isPelunasan,
                'result' => $result,
            ]);

            return response()->json(['message' => 'Callback diproses sukses'], 200);
        } catch (Throwable $e) {
            Log::error('Gagal memproses callback Xendit', [
                'external_id' => $externalId,
                'id_transaksi' => $transactionId,
                'status' => $status,
                'pelunasan' => $isPelunasan,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json(['message' => 'Callback gagal diproses'], 500);
        }
    }

    private function processUnreservedStock(string $transactionId): void
    {
        $hasProcessedQty = Schema::hasColumn('detail_pesanan', 'qty_stok_dipotong');

        $details = DB::table('detail_pesanan')
            ->where('id_transaksi', $transactionId)
            ->lockForUpdate()
            ->get();

        foreach ($details as $detail) {
            $processed = $hasProcessedQty ? (int) ($detail->qty_stok_dipotong ?? 0) : 0;
            $delta = max(0, (int) $detail->qty - $processed);

            if ($delta === 0) {
                continue;
            }

            $menu = DB::table('menu')
                ->where('id_menu', $detail->id_menu)
                ->lockForUpdate()
                ->first();

            if (!$menu) {
                throw new \RuntimeException('Menu ID ' . $detail->id_menu . ' tidak ditemukan saat callback diproses.');
            }

            if ((int) $menu->stok < $delta) {
                throw new \RuntimeException(
                    'Stok ' . ($menu->nama_menu ?? ('menu #' . $detail->id_menu)) .
                    ' tidak mencukupi. Dibutuhkan ' . $delta . ', tersedia ' . (int) $menu->stok . '.'
                );
            }

            DB::table('menu')
                ->where('id_menu', $detail->id_menu)
                ->decrement('stok', $delta);

            if ($hasProcessedQty) {
                DB::table('detail_pesanan')
                    ->where('id_detail_pesanan', $detail->id_detail_pesanan)
                    ->update([
                        'qty_stok_dipotong' => (int) $detail->qty,
                        'updated_at' => now(),
                    ]);
            }
        }
    }

    private function firstScalar(array $values): string
    {
        foreach ($values as $value) {
            if (is_string($value) || is_numeric($value)) {
                $value = trim((string) $value);
                if ($value !== '') {
                    return $value;
                }
            }
        }

        return '';
    }

    private function moneyValue(mixed $value): float
    {
        if (is_array($value)) {
            $value = $value['value'] ?? $value['amount'] ?? 0;
        }

        if (is_object($value)) {
            $value = $value->value ?? $value->amount ?? 0;
        }

        return is_numeric($value) ? (float) $value : 0.0;
    }
}
