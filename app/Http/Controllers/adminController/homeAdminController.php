<?php

namespace App\Http\Controllers\adminController;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pelangganModel;
use App\Models\reservasi;
use App\Models\Transaksi;
use App\Models\Meja;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class homeAdminController extends Controller
{
    // home admin
    public function home()
    {

        // ===== DATA CHART RESERVASI (punyamu yang sekarang) =====
        $reservasi = Reservasi::selectRaw('tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // ambil range tanggal dari data reservasi
        $start = Carbon::parse($reservasi->min('tanggal'));
        $end   = Carbon::parse($reservasi->max('tanggal'));

        $period = CarbonPeriod::create($start, $end);

        $map = $reservasi->pluck('total', 'tanggal');

        $labels = [];
        $data = [];

        foreach ($period as $date) {
            $tgl = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $data[] = $map[$tgl] ?? 0;
        }


        // ===== TOTAL TRANSAKSI VALID =====
        $totalRevenue = Transaksi::whereIn('status', ['tervalidasi', 'selesai'])
            ->sum('total');



        // Jumlah Meja    
        // $jumlahMejaIndoor = Meja::where('ruangan', 'Indoor')->count();
        // $jumlahMejaOutdoor = Meja::where('ruangan', 'Outdoor')->count();
        // $totalMeja = $jumlahMejaIndoor + $jumlahMejaOutdoor;

        $totalMeja = DB::table('meja')->count();
        // dd($totalMeja);

        // Jumlah Pelanggan
        $jumlahPelanggan = pelangganModel::count();

        // Pesanan Masuk
        $pesananMasuk = DB::table('transaksi')->count();

        // dd($pesananMasuk);

        return view('admin.dashboard', compact(
            'labels',
            'data',
            'totalRevenue',
            // 'jumlahMejaIndoor',
            // 'jumlahMejaOutdoor',
            'totalMeja',
            'jumlahPelanggan',
            'pesananMasuk',
        ));
    }

    public function dataUser()
    {

        $reservasi = reservasi::all();
        // atau pakai where jika perlu

        return view('admin.datauser', compact('reservasi'));
    }

    public function mejaCount()
    {
        $jumlahMejaIndoor = Meja::where('ruangan', 'Indoor')->count();
        $jumlahMejaOutdoor = Meja::where('ruangan', 'Outdoor')->count();

        $totalMeja = $jumlahMejaIndoor + $jumlahMejaOutdoor;

        return view('admin.dashboard', compact(
            'jumlahMejaIndoor',
            'jumlahMejaOutdoor',
            'totalMeja'
        ));
    }

    // transaksi langsung admin
    public function transaksiLangsung()
    {
        $labels = 'data';
        $menus = DB::table('menu')->orderBy('kategori')->orderBy('nama_menu')->get();
 
        // Daftar kategori diambil dinamis dari data yang ada,
        // jadi kalau pengelola nambah kategori baru di DB, tab-nya otomatis muncul.
        $kategoriList = $menus->pluck('kategori')->unique()->values();
 
        return view('admin.transaksiLangsung', [
            'menus'        => $menus,
            'kategoriList' => $kategoriList,
        ]);
        
        // return view('admin.transaksiLangsung', compact(
        //     'labels',
        // ));
    }
}
