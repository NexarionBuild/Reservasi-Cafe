<?php

namespace App\Http\Controllers\adminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\reservasi;
use App\Models\Transaksi;
use App\Models\MenuModel;
use App\Models\Meja;
use Illuminate\Support\Facades\DB;

class dataReservasi extends Controller
{
    public function reservasiData(Request $request)
    {

        $perPage = $request->get('per_page', 5);
        $search  = $request->get('search');

        $reservasi = Reservasi::with(['pelanggan', 'meja'])
            ->join('transaksi', 'transaksi.id_reservasi', '=', 'reservasi.id_reservasi')
            ->join('reservasi_meja', 'reservasi_meja.id_reservasi', '=', 'reservasi.id_reservasi')
            ->join('meja', 'meja.id_meja', '=', 'reservasi_meja.id_meja')
            ->select(
                'reservasi.*',
                'transaksi.status',
                'reservasi_meja.id_meja',
                'meja.kode_meja',
                'transaksi.id_transaksi',
            )
            // ->when($search, function ($query, $search) {
            //     $query->whereHas('transaksi', function ($q) use ($search) {
            //         $q->where('id_transaksi', 'like', "%{$search}%");
            //     });
            // })
            ->when($search, function ($query, $search) {
                // PERBAIKAN: Gunakan where langsung ke kolom tabel transaksi, bukan whereHas
                $query->where('transaksi.id_transaksi', 'like', "%{$search}%");
            })
            ->orderBy('reservasi.created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // dd($reservasi);

        return view('admin.riwayat', compact('reservasi', 'search'));
    }


    public function hapusReservasi($id)
    {
        DB::beginTransaction();

        try {
            // Ambil reservasi
            $reservasi = Reservasi::findOrFail($id);

            // Ambil semua transaksi milik pelanggan ini
            $transaksiIds = DB::table('transaksi')
                ->where('id_pelanggan', $reservasi->id_pelanggan)
                ->pluck('id_transaksi');

            // Hapus detail pesanan
            DB::table('detail_pesanan')
                ->whereIn('id_transaksi', $transaksiIds)
                ->delete();

            // Hapus transaksi
            DB::table('transaksi')
                ->where('id_reservasi', $reservasi->id_reservasi)
                ->delete();

            // Hapus reservasi
            $reservasi->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Reservasi dan seluruh pesanan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus reservasi.');
        }
    }

    public function detail($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);

            $reservasi = Reservasi::with('meja')
                ->where('id_reservasi', $transaksi->id_reservasi)
                ->first();

            if (!$reservasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservasi tidak ditemukan'
                ]);
            }

            return response()->json([
                'success' => true,
                'transaksi' => $transaksi,
                'reservasi' => $reservasi
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data',
                'error' => $e->getMessage() // penting untuk debugging
            ], 500);
        }
    }


    public function detailJson($id)
    {
        /**
         * STEP 1
         * Ambil data reservasi + pelanggan
         */
        $reservasi = DB::table('reservasi')
            ->leftJoin('pelanggan', 'reservasi.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->leftJoin('reservasi_meja', 'reservasi_meja.id_reservasi', '=', 'reservasi.id_reservasi')
            ->leftJoin('meja', 'reservasi_meja.id_meja', '=', 'meja.id_meja')
            ->where('reservasi.id_reservasi', $id)
            ->select(
                'reservasi.id_pelanggan',
                'reservasi_meja.id_meja',
                'meja.id_meja',
                'meja.kode_meja',
                'pelanggan.nama_pelanggan',
                'reservasi.waktu',
                'reservasi.jumlah_tamu',
                'reservasi.bukti_pembayaran'
            )
            ->first();

        // Jika ID tidak ada (sudah dihapus)
        if (!$reservasi) {
            return response()->json([
                'message' => 'Data reservasi tidak ditemukan',
                'pesanan' => []
            ]);
        }


        /**
         * STEP 2
         * Ambil transaksi milik pelanggan tsb
         */
        $transaksi = DB::table('transaksi')
            ->where('id_reservasi', $id)
            ->select(
                'id_transaksi',
                'status',
                'total',
                'metode_pembayaran'
            ) // tambahkan kolom yang dibutuhkan
            ->get(); // Bisa banyak transaksi, jadi pakai get()



        /**
         * STEP 3
         * Ambil detail pesanan + menu
         */
        $pesanan = DB::table('detail_pesanan')
            ->join('transaksi', 'detail_pesanan.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('menu', 'detail_pesanan.id_menu', '=', 'menu.id_menu')
            ->where('transaksi.id_reservasi', $id)
            ->select(
                'menu.id_menu',
                'menu.nama_menu',
                'detail_pesanan.catatan',
                DB::raw('SUM(detail_pesanan.qty) as total_qty'),
                'menu.harga',
                DB::raw('SUM(detail_pesanan.qty * menu.harga) as subtotal')
            )
            ->groupBy(
                'menu.id_menu',
                'menu.nama_menu',
                'menu.harga',
                'detail_pesanan.catatan'
            )
            ->get();


        /**
         * STEP 4
         * Return JSON
         */
        return response()->json([
            'nama_pelanggan' => $reservasi->nama_pelanggan,
            'waktu' => $reservasi->waktu,
            'jumlah_tamu' => $reservasi->jumlah_tamu,
            'transaksi' => $transaksi,   // <-- tambahkan ini
            'pesanan' => $pesanan,
            'bukti_pembayaran' => $reservasi->bukti_pembayaran,
            'kode_meja' => $reservasi->kode_meja
        ]);
    }


    public function konfirmasiKehadiran($id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->status_kehadiran = 'hadir';
        $reservasi->save();

        return redirect()->back()->with('success', 'Kehadiran pelanggan berhasil dikonfirmasi.');
    }

    public function validasiTransaksi($id)
    {
        \Log::info('VALIDASI ID:', ['id' => $id]);

        $idPengelola = session('id_pengelola');

        \Log::info('ID PENGELOLA SESSION:', ['id_pengelola' => $idPengelola]);
        if (!$idPengelola) {
            return response()->json([
                'message' => 'Pengelola belum login'
            ], 401);
        }

        // dd($transaksi);


        DB::beginTransaction();

        try {
            // 1️ Ambil transaksi
            $transaksi = Transaksi::findOrFail($id);

            if ($transaksi->status === 'tervalidasi') {
                return response()->json([
                    'message' => 'Transaksi sudah tervalidasi'
                ], 400);
            }

            // 2️ Update transaksi
            $transaksi->status = 'tervalidasi';
            $transaksi->save();

            // 3️ Update reservasi (BERDASARKAN ID PELANGGAN)
            Transaksi::where('id_pelanggan', $transaksi->id_pelanggan)
                ->update([
                    'id_pengelola' => $idPengelola
                ]);

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil divalidasi'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // dd($e);

            return response()->json([
                'message' => 'Gagal melakukan validasi transaksi',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function ubahStatusTransaksi(Request $request)
    {
        // dd($request->all());

        $id_reservasi = $request->id_reservasi;

        DB::table('transaksi')
            ->where('id_reservasi', $id_reservasi) // Cari data yang mau diupdate
            ->update([
                'status' => 'selesai',
                'updated_at' => now(), // Manual karena Query Builder tidak otomatis mengisi timestamps
            ]);

        return redirect()->back()->with('success', 'meja sudah dikosongkan!');
    }
}
