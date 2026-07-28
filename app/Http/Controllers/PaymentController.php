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

        // ambil data session atau data dari request untuk membuat invoice
        $id_pengelola = session('id_pengelola');
        $id_pelanggan = session('id_pelanggan');
        $ruangan = Session::get('RuanganDipilih');
        $mejaDipilih   = Session::get('mejaDipilih');
        $menuReservasi = Session::get('menuReservasi');

        // dd($id_pelanggan, $id_pengelola);

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

        $totalAmount = 0;

        // Siapkan data untuk invoice
        foreach ($keranjang as $item) {
            // Gabungkan nama menu dengan catatannya jika ada

            $namaMenu = $item['nama'];
            if (!empty($item['catatan'])) {
                $namaMenu .= ' (' . $item['catatan'] . ')';
            }
            $menuList[] = $namaMenu . ' x' . $item['qty'];


            // Untuk deskripsi ringkas (tetap pertahankan sebagai cadangan data)

            // PERBAIKAN UTAMA: Setiap item wajib dibungkus dengan Class `InvoiceItem` baru
            $xenditItems[] = [
                'name'     => (string) Str::limit($namaMenu, 50, '...'),
                'price'    => (float) $item['harga'],
                'quantity' => (int) $item['qty'],
                'category' => (string) ($item['kategori'] ?? 'Makanan & Minuman')
            ];

            // Hitung total harga
            $totalAmount += ($item['harga'] * $item['qty']);
        }

        $totalAmount = $totalAmount / 2;

        // dd($totalAmount);

        // 2. Gabungkan untuk description (max 255 karakter)
        $descriptionText = implode(', ', $menuList);
        $safeDescription = Str::limit($descriptionText, 250, '...');

        if (empty($safeDescription)) {
            $safeDescription = "Pembayaran Pesanan Makanan";
        }

        // Buat External ID unik (contoh format: INV-timestamp)
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
                'description'          => 'pembayaran reservasi DP ',
                'invoice_duration'     => 900,

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
                'jumlah_tamu'      => $dataReservasi['jumlahTamu'] ?? 1,
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
                'total'             => 0,
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
                Session::forget([$cartKey]);
            } else {
                Session::forget(['keranjangKasir']);
            }
            Session::forget(['dataReservasi', 'RuanganDipilih', 'mejaDipilih', 'menuReservasi']);


            // Redirect pengguna ke halaman pembayaran Xendit
            return redirect($response['invoice_url']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // viwe tambahan menu
    public function TambahanMenu($id)
    {
        $keranjang = DB::table('transaksi')
            ->join('detail_pesanan', 'transaksi.id_transaksi', 'detail_pesanan.id_transaksi')
            ->join('menu', 'menu.id_menu', 'detail_pesanan.id_menu')
            ->select(
                // 'transaksi.id_transaksi',
                'detail_pesanan.id_transaksi',
                'detail_pesanan.id_menu',
                'menu.nama_menu',
                'menu.harga',
                'detail_pesanan.qty',
                'detail_pesanan.catatan',
            )
            ->where('transaksi.id_transaksi', '=', $id)
            ->get();

        $labels = 'data';
        $menus = DB::table('menu')->orderBy('kategori')->orderBy('nama_menu')->get();

        // Daftar kategori diambil dinamis dari data yang ada,
        // jadi kalau pengelola nambah kategori baru di DB, tab-nya otomatis muncul.
        $kategoriList = $menus->pluck('kategori')->unique()->values();
        // dd($keranjang);
        return view('admin.transaksiLangsung', [
            'id_transaksi' => $id,
            'menus'        => $menus,
            'kategoriList' => $kategoriList,
            'keranjang' => $keranjang,
        ]);
    }

    // proses tambahan menu
    public function tambahanPesanan(Request $request, $id)
    {
        // dd($request->items, $id);
        // 1. Ambil string JSON dari input form (ganti 'nama_input_anda' dengan name pada tag input/hidden Anda)
        $mentah = $request->input('items');
        $items = collect(json_decode($mentah, true))->toArray();

        // dd($items);

        // 2. Lakukan perulangan untuk mengecek setiap item
        foreach ($items as $item) {
            // Cari apakah data dengan id_transaksi dan id_menu ini sudah ada di DB
            $existingData = DetailPesanan::where('id_transaksi', $id)
                ->where('id_menu', $item['id'])
                ->first();

            if ($existingData) {
                // JIKA DATA ADA: Cek apakah qty atau catatan berubah
                if ($existingData->qty != $item['qty'] || $existingData->catatan != $item['catatan']) {
                    // Hanya update jika ada perubahan nilai
                    $existingData->update([
                        'qty'     => $item['qty'] + $existingData->qty,
                        'catatan' => $item['catatan']
                    ]);
                }
                // JIKA qty dan catatan sama, block if ini dilewati (TIDAK ADA UPDATE KE DB)

            } else {
                // JIKA DATA BELUM ADA: Buat data baru (INSERT)
                DetailPesanan::create([
                    'id_transaksi' => $id,
                    'id_menu'      => $item['id'],
                    'qty'          => $item['qty'],
                    'catatan'      => $item['catatan']
                ]);
            }
        }
        return redirect()->back()->with('success', 'Pesanan tambahan berhasil diperbarui!');
    }

    // checkout lunas
    public function checkoutLunas($id)
    {
        // dd($id);

        $pesanan = DB::table('detail_pesanan')
            ->join('menu', 'menu.id_menu', 'detail_pesanan.id_menu')
            ->where('detail_pesanan.id_transaksi', '=', $id)
            ->select(
                'detail_pesanan.qty',
                'menu.nama_menu as name',
                'menu.harga',
                'menu.kategori',
            )
            ->get();

        $dp = DB::table('transaksi')
            ->where('transaksi.id_transaksi', '=', $id)
            ->select(
                'transaksi.total as dp'
            )
            ->first();

        $dp = $dp->dp;

        // dd($dp);

        $payment_method = [
            'QRIS',
        ];

        $detail_pesanan = json_decode(json_encode($pesanan->toArray()), true);

        $totalAmount = 0;

        // Siapkan data untuk invoice
        foreach ($detail_pesanan as $item) {
            // Gabungkan nama menu dengan catatannya jika ada
            $namaMenu = $item['name'];
            $menuList[] = $namaMenu . ' x' . $item['qty'];


            // Untuk deskripsi ringkas (tetap pertahankan sebagai cadangan data)

            // PERBAIKAN UTAMA: Setiap item wajib dibungkus dengan Class `InvoiceItem` baru
            $xenditItems[] = [
                'name'     => (string) Str::limit($namaMenu, 50, '...'),
                'price'    => (float) $item['harga'],
                'quantity' => (int) $item['qty'],
                'category' => (string) ($item['kategori'] ?? 'Makanan & Minuman')
            ];

            // Hitung total harga
            $totalAmount += ($item['harga'] * $item['qty']);
        }

        // $idTransaksiBaru = (int) ($id . "-11");

        try {
            //code...
            $apiInstance = new InvoiceApi();
            $createInvoiceRequest = new CreateInvoiceRequest([
                'external_id'          => (string) $id,

                // KUNCI UTAMA: Nilai ini WAJIB sama persis dengan total penjumlahan di atas
                'amount'               => (float) $totalAmount - $dp,

                'payer_email'          => 'customer@email.com',
                'description'          => 'Pelunasan Reservasi (Total Bayar DP: ' . $dp . ')',
                'invoice_duration'     => 900,

                // Kirim array asosiatif item murni
                'items'                => $xenditItems,

                'invoice_callback_url' => 'https://deed-raisin-thesaurus.ngrok-free.dev',
                'success_redirect_url' => url(route('reservasiData')),
                'failure_redirect_url' => url(route('reservasiData')),

                'payment_methods'      => $payment_method
            ]);

            // DB::table('transaksi')
            // ->where('id_transaksi', '=', $id)
            // ->update([
            //     // 'id_transaksi' => $idTransaksiBaru,
            //     'status' => 'selesai',
            //     'updated_at'   => now() // Opsional: jika tabel memiliki kolom timestamp
            // ]);

            // DB::table('detail_pesanan')
            // ->where('id_transaksi', '=', $id)
            // ->update([
            //     'id_transaksi' => $idTransaksiBaru,
            //     'updated_at'   => now() // Opsional: jika tabel memiliki kolom timestamp
            // ]);
            $response = $apiInstance->createInvoice($createInvoiceRequest);

            return redirect($response['invoice_url']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function checkoutTunai(Request $request)
    {
        // return redirect()->back()->with('success', 'Pesanan tambahan berhasil diperbarui!');

        // dd($request->all());

        $rsrvasi = DB::table('reservasi')
            ->join('transaksi', 'transaksi.id_reservasi', 'reservasi.id_reservasi')
            ->select('transaksi.id_transaksi', 'transaksi.total')
            ->where('reservasi.id_reservasi', '=', $request->id)
            ->first();

        try {
            //code...
            DB::table('transaksi')->where('id_transaksi', $rsrvasi->id_transaksi)->update([
                'total'             => $rsrvasi->total + $request->bayar,
                'metode_pembayaran' => 'tunai',
                'status'            => 'selesai',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            return redirect()->back()->with('success', 'pembayaran tunai berhasil');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('success', $th);
        }
    }

    // batal reservasi
    public function batalReservasi($id)
    {
        // $transaksi = DB::table('transaksi')->where('id_transaksi', $id);

        try {
            //code...
            DB::table('transaksi')->where('id_transaksi', $id)->update([
                'status'            => 'gagal',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            return redirect()->back()->with('success', 'status berhasil diperbarui!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('success', 'status gagal diperbarui!' . $th);
        }
    }
}
