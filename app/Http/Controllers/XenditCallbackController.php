<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class XenditCallbackController extends Controller
{
    public function handleCallback(Request $request)
    {
        // 1. Validasi Token Keamanan dari Xendit
        $callbackToken = $request->header('x-callback-token');
        if ($callbackToken !== config('services.xendit.callback_token')) {
            return response()->json(['message' => 'Token tidak valid'], 403);
        }

        // 2. Ambil data transaksi yang dikirim Xendit Invoice
        $externalId = $request->input('external_id');
        $status = $request->input('status'); // PAID atau EXPIRED

        // Akalin data uji coba teks dari tombol 'Test & Save' dashboard Xendit
        // if (!is_numeric($externalId)) {
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'Koneksi berhasil! Data uji coba teks dari Xendit dilewati.'
        //     ], 200);
        // }

        // 3. Cari data transaksi di database
        $order = DB::table('transaksi')->where('id_transaksi', $externalId)->first();

        // Kunci lolos validasi URL Xendit jika data tidak ada di DB
        if (!$order) {
            return response()->json([
                'status' => 'success',
                'message' => 'Koneksi berhasil! Data uji coba Xendit diterima.'
            ], 200);
        }

        // 4. PERBAIKAN UTAMA: Cara update database yang benar untuk Query Builder (DB::table)
        if ($status === 'PAID') {
            DB::table('transaksi')
                ->where('id_transaksi', $externalId)
                ->update([
                    'status' => 'tervalidasi', // Menjaga konsistensi typo kolom Anda
                    'metode_pembayaran' => $request->input('payment_method', 'Xendit'), // Otomatis catat metode (QRIS/EWALLET)
                    'updated_at' => now(),
                ]);
        } elseif ($status === 'EXPIRED') {
            DB::table('transaksi')
                ->where('id_transaksi', $externalId)
                ->update([
                    'status' => 'EXPIRED',
                    'updated_at' => now(),
                ]);
        }

        // 5. Berikan respon sukses ke Xendit
        return response()->json(['message' => 'Callback diproses sukses'], 200);
    }
}
