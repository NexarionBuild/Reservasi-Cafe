<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan ini ada di bagian paling atas controller

class PesananController extends Controller
{
    public function riwayat()
    {
        // 1. Ambil ID Pelanggan dari session login
        $idPelanggan = session('id_pelanggan');

        if (!$idPelanggan) {
            return redirect()->route('login')->with('gagal', 'Silakan login terlebih dahulu.');
        }

        $idPelanggan = (int) $idPelanggan;

        // ========================================================
        // 2. QUERY TRANSAKSI AKTIF / BERJALAN (Termasuk status 'menunggu')
        // ========================================================
        $pesananSukses = DB::table('transaksi')
            ->where('id_pelanggan', $idPelanggan)
            ->whereIn(DB::raw('LOWER(status)'), ['menunggu', 'tervalidasi','selesai']) 
            ->orderBy('id_transaksi', 'desc')
            ->get();

        // dd($pesananSukses);

        // Ambil data detail item menu untuk setiap transaksi aktif
        foreach ($pesananSukses as $transaksi) {
            $transaksi->items = DB::table('detail_pesanan')
                ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id_menu')
                ->where('detail_pesanan.id_transaksi', $transaksi->id_transaksi)
                ->select('detail_pesanan.qty', 'menu.nama_menu', 'menu.harga', 'menu.kategori', 'detail_pesanan.catatan')
                ->get();
        }

        // ========================================================
        // 3. QUERY TRANSAKSI GAGAL / BATAL
        // ========================================================
        $pesananGagal = DB::table('transaksi')
            ->where('id_pelanggan', $idPelanggan)
            ->whereIn(DB::raw('LOWER(status)'), ['batal', 'gagal', 'expired'])
            ->orderBy('id_transaksi', 'desc')
            ->get();

        // Ambil data detail item menu untuk setiap transaksi gagal
        foreach ($pesananGagal as $gagal) {
            $gagal->items = DB::table('detail_pesanan')
                ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id_menu')
                ->where('detail_pesanan.id_transaksi', $gagal->id_transaksi)
                ->select('detail_pesanan.qty', 'menu.nama_menu', 'detail_pesanan.catatan')
                ->get();
        }

        // 4. Kirim data ke view
        return view('User.riwayat-pesanan', compact('pesananSukses', 'pesananGagal'));
    }


    public function downloadNota($id_transaksi)
    {
        $idPelanggan = session('id_pelanggan');
        if (!$idPelanggan) {
            return redirect()->route('login');
        }

        // 1. Ambil data transaksi milik user ini dari database
        $transaksi = DB::table('transaksi')
            ->where('id_transaksi', $id_transaksi)
            ->where('id_pelanggan', $idPelanggan)
            ->first();

        if (!$transaksi) {
            return redirect()->back()->with('gagal', 'Transaksi tidak ditemukan.');
        }

        // Blokir unduhan jika statusnya masih menunggu konfirmasi
        if (strtolower($transaksi->status) == 'menunggu') {
            return redirect()->back()->with('gagal', 'Nota belum bisa diunduh sebelum dikonfirmasi admin.');
        }

        // 2. Ambil data reservasi pendukung (meja/ruangan) berdasarkan id_reservasi yang ada di transaksi
        $reservasi = DB::table('reservasi')->where('id_reservasi', $transaksi->id_reservasi)->first();
        $reservasi_meja = DB::table('reservasi_meja')->where('id_reservasi', $transaksi->id_reservasi)->first();

        $meja = null;
        $ruangan = null;

        if ($reservasi) {
            
            $foreignKeyMeja = $reservasi_meja->id_meja ?? null; 

            if ($foreignKeyMeja) {
                $m = DB::table('meja')->where('id_meja', $foreignKeyMeja)->first();
                $meja = $m->kode_meja;
                $ruangan = $m->ruangan;
                // Ambil data ruangan jika meja terhubung dengan ruangan
                // if ($meja && isset($meja->id_ruangan)) {
                //     $ruangan = DB::table('ruangan')->where('id_ruangan', $meja->id_ruangan)->first();
                // }
            }
        }

        // 3. Ambil data menu yang dibeli dari tabel detail_pesanan (bukan session keranjang)
        $items = DB::table('detail_pesanan')
            ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id_menu')
            ->where('detail_pesanan.id_transaksi', $id_transaksi)
            ->select('detail_pesanan.qty', 'menu.nama_menu', 'menu.harga')
            ->get();

        // Mapping agar struktur variabelnya mirip dengan array $pesanan milikmu
        $pesanan = [];
        foreach ($items as $item) {
            $pesanan[] = [
                'nama'  => $item->nama_menu,
                'harga' => $item->harga,
                'qty'   => $item->qty,
            ];
        }

        $totalBayar = $transaksi->total;

        // 4. Render ke view PDF menggunakan variabel yang mirip dengan fungsi detailTransaksi milikmu
        $pdf = Pdf::loadView('User.nota-pdf', compact(
            'transaksi',
            'reservasi',
            'meja',
            'ruangan',
            'pesanan',
            'totalBayar'
        ));

        return $pdf->download('Nota-Kopi-satu-' . $id_transaksi . '.pdf');
    }


}