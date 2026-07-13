<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\Auth;
// use App\Models\Order;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\InvoiceItem;
use Xendit\Invoice\CreateInvoiceRequest;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Inisialisasi API Key Xendit
        Configuration::getDefaultConfiguration()->setApiKey(config('services.xendit.api_key'));
    }

    public function checkout(Request $request)
    {

        // dd(Auth::pengelola());


        // ambil data session atau data dari request untuk membuat invoice
        $id_pengelola = session('id_pengelola');
        $id_pelanggan = session('id_pelanggan');
        $ruangan = Session::get('RuanganDipilih');
        $mejaDipilih   = Session::get('mejaDipilih');
        $menuReservasi = Session::get('menuReservasi');

        if ($id_pengelola != null) {
            # code...
            $dataReservasi = [
                "jumlahTamu" => "1",
                "waktu" => now()->format('H:i'),
                "tanggal" => now()->format('Y-m-d'),
            ];

            $keranjang     = Session::get('keranjangKasir');
        } else {
            $dataReservasi = Session::get('dataReservasi');
            $cartKey       = 'keranjang_' . $id_pelanggan;
            $keranjang     = Session::get($cartKey, []);
        }

        // dd($dataReservasi, $ruangan, $mejaDipilih, $keranjang);

        $totalAmount = 0;

        // 1. Siapkan data untuk invoice
        foreach ($keranjang as $item) {
            // Gabungkan nama menu dengan catatannya jika ada

            $namaMenu = $item['nama'];
            if (!empty($item['catatan'])) {
                $namaMenu .= ' (' . $item['catatan'] . ')';
            }
            $menuList[] = $namaMenu . ' x' . $item['qty'];


            // Untuk deskripsi ringkas (tetap pertahankan sebagai cadangan data)

            // 2. PERBAIKAN UTAMA: Setiap item wajib dibungkus dengan Class `InvoiceItem` baru
            $xenditItems[] = [
                'name'     => (string) Str::limit($namaMenu, 50, '...'),
                'price'    => (float) $item['harga'],
                'quantity' => (int) $item['qty'],
                'category' => (string) ($item['kategori'] ?? 'Makanan & Minuman')
            ];

            // Hitung total harga
            $totalAmount += ($item['harga'] * $item['qty']);
        }

        // 2. Gabungkan untuk description (max 255 karakter)
        $descriptionText = implode(', ', $menuList);
        $safeDescription = Str::limit($descriptionText, 250, '...');

        if (empty($safeDescription)) {
            $safeDescription = "Pembayaran Pesanan Makanan";
        }


        // dd($safeDescription);


        // 1. Buat External ID unik (contoh format: INV-timestamp)
        $externalId = time();

        $id_reservasi = rand();

        // data payment method
        if ($id_pengelola != null) {
            $payment_method = [
                'QRIS',
                'BCA',
                'MANDIRI',
                'BRI',
            ];
        } else {
            $payment_method = [
                'QRIS',
                'DANA',
                'OVO',
                'SHOPEEPAY',
                'BCA',
                'MANDIRI',
                'BRI',
            ];
        }

        if ($id_pelanggan != null) {
            $urlBerhasil = url('/riwayat-transaksi');
            $urlGagal = url('/riwayat-transaksi');
        } else {
            $urlBerhasil = url(route('admin.transaksiLangsung'));
            $urlGagal = url(route('admin.transaksiLangsung'));;
        }

        try {
            // KIRIM KE XENDIT UNTUK BUAT INVOICE
            $apiInstance = new InvoiceApi();
            $createInvoiceRequest = new CreateInvoiceRequest([
                'external_id'          => (string) $externalId,

                // KUNCI UTAMA: Nilai ini WAJIB sama persis dengan total penjumlahan di atas
                'amount'               => (float) $totalAmount,

                'payer_email'          => 'customer@email.com',
                'description'          => 'pembayaran untuk pesanan ',
                'invoice_duration'     => 86400,

                // Kirim array asosiatif item murni
                'items'                => $xenditItems,

                'invoice_callback_url' => 'https://deed-raisin-thesaurus.ngrok-free.dev',
                'success_redirect_url' => $urlBerhasil,
                'failure_redirect_url' => $urlGagal,

                'payment_methods'      => $payment_method
            ]);

            $response = $apiInstance->createInvoice($createInvoiceRequest);

            DB::table('reservasi')->insert([
                'id_reservasi'     => $id_reservasi,
                'id_pelanggan'     => $id_pelanggan,
                'tanggal'          => $dataReservasi['tanggal'],
                'waktu'            => $dataReservasi['waktu'],
                'jumlah_tamu'      => $dataReservasi['jumlah_tamu'] ?? 1,
                'ruangan'          => $ruangan,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            DB::table('reservasi_meja')->insert([
                'id_reservasi'     => $id_reservasi,
                'id_meja'          => $mejaDipilih,
            ]);

            DB::table('transaksi')->insert([
                'id_transaksi'      => $externalId,
                'id_pelanggan'      => $id_pelanggan,
                'id_reservasi'      => $id_reservasi,
                'id_pengelola'      => $id_pengelola,
                'total'             => $totalAmount,
                'status'            => 'menunggu',
                'metode_pembayaran' => '',
                'checkout_url'      => $response['invoice_url'],
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            foreach ($keranjang as $item) {
                DetailPesanan::insert([
                    'id_transaksi' => $externalId,
                    'id_menu'      => $item['id'],
                    'qty'          => $item['qty'],
                    'catatan'      => $item['catatan'],
                ]);
            }

            // Menghapus beberapa data session sekaligus
            if ($id_pelanggan != null) {
                # code...
                Session::forget([$cartKey]);
            }else{
                Session::forget(['keranjangKasir']);
            }
            Session::forget(['dataReservasi', 'RuanganDipilih', 'mejaDipilih', 'menuReservasi']);


            // Redirect pengguna ke halaman pembayaran Xendit
            return redirect($response['invoice_url']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
