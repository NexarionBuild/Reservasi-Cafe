<?php
use App\Http\Controllers\adminController\dataMenuController;
use App\Http\Controllers\adminController\dataReservasi;
use App\Http\Controllers\adminController\dataUser;
use App\Http\Controllers\adminController\homeAdminController;
use App\Http\Controllers\adminController\mejaController;
use App\Http\Controllers\DetailPesananController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\regisController;
use App\Http\Controllers\reservasiController;
use App\Http\Controllers\XenditCallbackController;
// use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;



// ========================================== MENU ==================================================================

// MENU
Route::get('/menu-minuman', [MenuController::class, 'menuMinuman'])->name('menu-minuman');
Route::get('/menu-makanan', [MenuController::class, 'menuMakanan'])->name('menu-makanan');
Route::get('/makanan/{id}/detail', [MenuController::class, 'detailMakanan'])->name('detail-makanan');
Route::get('/minuman/{id}/detail', [MenuController::class, 'detailMinuman'])->name('detail-minuman');
Route::get('/keranjang', [MenuController::class, 'keranjang'])->name('keranjang');
Route::post('/keranjang/pilih', [MenuController::class, 'pilihItem'])->name('keranjang.pilih');
Route::delete('/keranjang/hapus/{index}', [MenuController::class, 'removeItem'])->name('remove-item');
Route::put('/keranjang/update/{index}', [MenuController::class, 'updateQty'])->name('update-qty');
Route::post('/menu/add-to-cart', [MenuController::class, 'addToCart'])->name('add-to-cart');

Route::post('/reservasi/from-menu/{id}', [MenuController::class, 'fromMenu'])->name('reservasi.fromMenu');

// Route::get('/reservasi', [MenuController::class, 'formReservasi'])->name('reservasi');

// ========================================== PELANGGAN ==================================================================

// LOGIN
// get login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');


// POST login pelanggan dan pengelola
Route::post('/login-pelanggan', [LoginController::class, 'loginPelanggan'])->name('login-pelanggan');


// // halaman registrasi
// get regis
Route::get('/register', [RegisController::class, 'showRegis'])->name('register');


// post regis
Route::post('/form-register', [RegisController::class, 'store'])->name('form-register');


// logout pelanggan
Route::post('', [LoginController::class, 'logutPelanggan'])->name('logout-pelanggan');


// Home
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/home-pelanggan', [HomeController::class, 'homepelanggan'])->name('home-login');

// ========================================== RESERVASI PELANGGAN ==================================================================
// reservasi
Route::get('/reservasi', [App\Http\Controllers\reservasiController::class, 'showResevasi'])->name('reservasi');
Route::post('/form-reservasi', [App\Http\Controllers\reservasiController::class, 'formReservasi'])->name('form-reservasi');


// tempat duduk
// POST dari form reservasi
Route::post('/tempat-duduk', [App\Http\Controllers\reservasiController::class, 'pilihTempatDuduk'])->name('tempat-duduk');


// GET untuk menampilkan halaman pilih tempat duduk
Route::get('/tempat-duduk', [App\Http\Controllers\reservasiController::class, 'showTempatDuduk'])->name('show-tempat-duduk');

// POST pilih tempat duduk
Route::post('/pilih-tempat', [reservasiController::class, 'pilihTempatDuduk'])->name('pilihTempatDuduk');

Route::get('/menu-minuman', [MenuController::class, 'menuMinuman'])->name('menu-minuman');


// detail pesanan
Route::get('/detail-pesanan', [App\Http\Controllers\reservasiController::class, 'detailPesanan'])->name('detail-pesanan');
Route::post('/upload-bukti', [App\Http\Controllers\reservasiController::class, 'uploadBukti'])->name('upload-bukti');

// chekout
Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
// chekout lunas
Route::post('/tambahan-pesanan/{id}', [PaymentController::class, 'tambahanPesanan'])->name('tambahanPesanan');
Route::get('/checkout-lunas/{id}', [PaymentController::class, 'checkoutLunas'])->name('checkout.lunas');
Route::post('/checkout-lunas/tunai', [PaymentController::class, 'checkoutTunai'])->name('checkout.tunai');
Route::get('/batal-reservasi/{id}', [PaymentController::class, 'batalReservasi'])->name('batal.reservasi');



// detail transaksi
Route::get('/detail-transaksi/{id}', [App\Http\Controllers\reservasiController::class, 'detailTransaksi'])->name('detail-transaksi');
Route::get('/download-transaksi/{id}', [App\Http\Controllers\reservasiController::class, 'downloadTransaksi'])->name('transaksi.download');


Route::get('/riwayat-transaksi', [App\Http\Controllers\PesananController::class, 'riwayat'])->name('pesanan.riwayat');
Route::get('/riwayat-transaksi/download/{id_transaksi}', [PesananController::class, 'downloadNota'])->name('pesanan.download');

Route::post('/transaksi/set-gagal', [App\Http\Controllers\reservasiController::class, 'setTransaksiGagal'])->name('transaksi.set-gagal');

// ========================================== ADMIN ==================================================================
// REGIS ADMIN

// get regis
Route::get('/register-admin', [RegisController::class, 'showRegisAdmin'])->name('register');


// post regis
Route::post('/form-registerAdmin', [RegisController::class, 'storeAdmin'])->name('form-registerAdmin');


// Home admin
Route::get('/admin/dashboard', [homeAdminController::class, 'home'])->name('admin.dashboard');

// transaksi langsung admin
Route::get('/admin/transaksi/tambahan-menu', [homeAdminController::class, 'transaksiLangsung'])->name('admin.transaksiLangsung');


// logout admin
Route::get('/logout-pengelola', [LoginController::class, 'logutPengelola'])->name('logout-admin');


// MENU
Route::get('/admin/makanan', [dataMenuController::class, 'showMakanan'])->name('showMakanan');
Route::get('/admin/minuman', [dataMenuController::class, 'showMinuman'])->name('showMinuman');


// FORM MENU
// Route::get('/tambah-minuman', [dataMenuController::class, 'formMinuman'])->name('formMinuman');
Route::get('/tambah-menu/{kategori}', [dataMenuController::class, 'formMenu'])->name('formMenu');
Route::post('/tambah-menu', [dataMenuController::class, 'addMenu'])->name('addMenu');


// AKSI MENU
Route::get('/admin/menu/{id}/edit', [dataMenuController::class, 'edit'])
 ->name('edit');
Route::post('/menu/{id}', [dataMenuController::class, 'update'])->name('update');
Route::delete('/menu/{id}', [dataMenuController::class, 'destroy'])->name('destroy');
Route::get('/menu/{id}', [dataMenuController::class, 'show'])->name('menu.show');


// DATA USER 
Route::delete('/admin/hapus-user/{id}', [dataUser::class, 'hapusUser'])->name('hapusUser');
Route::get('/admin/data-user', [dataUser::class, 'dataUserReservasi'])->name('dataUser');


// DATA RESERVASI
Route::get('/admin/data-reservasi', [dataReservasi::class, 'reservasiData'])->name('reservasiData');
Route::delete('/admin/hapus-reservasi/{id}', [dataReservasi::class, 'hapusReservasi'])->name('hapusReservasi');
Route::get('/admin/data-reservasi/{id}/detail', [dataReservasi::class, 'detail'])->name('detailReservasi');
Route::get('/admin/data-reservasi/{id}/detail-json', [dataReservasi::class, 'detailJson']);
Route::post('/admin/transaksi/{id}/validasi', [dataReservasi::class, 'validasiTransaksi'])->name('transaksi.validasi');
Route::post('/admin/reservasi/{id}/kehadiran', [dataReservasi::class, 'konfirmasiKehadiran'])->name('reservasi.kehadiran');
Route::delete('/admin/transaksi/{id}/hapus', [dataReservasi::class, 'hapusTransaksi'])->name('transaksi.hapus');
Route::post('/admin/transaksi-selesai', [dataReservasi::class, 'ubahStatusTransaksi'])->name('kosongkan.meja');
Route::get('/admin/data-reservasi/tambah-menu/{id}', [PaymentController::class, 'TambahanMenu'])->name('tambahan-menu');




//DATA MEJA
// Halaman Data Meja
Route::get('/admin/data-meja', [MejaController::class, 'showMeja'])->name('dataMeja.showMeja');
Route::get('/admin/form-meja', [MejaController::class, 'showFormMeja'])->name('dataMeja.showFormMeja');



Route::post('/admin/data-meja', [mejaController::class, 'store'])->name('dataMeja.store');
Route::delete('/admin/data-meja/{id}/hapus', [mejaController::class, 'destroy'])->name('dataMeja.destroy');
// Route::post('/admin/data-meja/add/', [dataReservasi::class, 'dataMeja'])->name('dataMeja.add');


// SETTING ADMIN
Route::get('/admin/setting', function () {
    return view('Admin.setting');
})->name('setting');




// Route untuk update foto cafe
Route::post('/admin/setting/update-foto-cafe', function (\Illuminate\Http\Request $request) {
    if ($request->hasFile('foto_cafe')) {
        $file = $request->file('foto_cafe');
        $file->move(public_path('images'), 'cafe.jpg');
    }
    return back()->with('success', 'Foto cafe berhasil diupdate!');
})->name('update-foto-cafe');


// Route untuk update tentang cafe
Route::post('/admin/setting/update-tentang-cafe', function () {
    // Logic update tentang cafe di sini
    // Contoh: Simpan ke database atau file
    return back()->with('success', 'Tentang cafe berhasil diupdate!');
})->name('update-tentang-cafe');


// Route untuk update footer
Route::post('/admin/setting/update-footer', function () {
    // Logic update footer di sini
    // Contoh: Simpan ke database atau file
    return back()->with('success', 'Footer berhasil diupdate!');
})->name('update-footer');


// Route untuk update Kopi Senja
Route::post('/admin/setting/update-kopi-senja', function (\Illuminate\Http\Request $request) {
    $isi = $request->input('kopi_senja');
    file_put_contents(base_path('resources/kopi_senja.txt'), $isi);
    return back()->with('success', 'Nama Kopi Senja berhasil diupdate!');
})->name('update-kopi-senja');


// Route untuk update Hubungi Kami
Route::post('/admin/setting/update-hubungi-kami', function (\Illuminate\Http\Request $request) {
    $data = [
        'telepon1' => $request->input('telepon')[0] ?? '',
        'telepon2' => $request->input('telepon')[1] ?? '',
        'alamat' => $request->input('alamat') ?? '',
    ];
    file_put_contents(base_path('resources/hubungi_kami.json'), json_encode($data, JSON_PRETTY_PRINT));
    return back()->with('success', 'Hubungi Kami berhasil diupdate!');
})->name('update-hubungi-kami');


// Route untuk update Lokasi
Route::post('/admin/setting/update-lokasi', function (\Illuminate\Http\Request $request) {
    $link = $request->input('maps_link') ?? '';
    // Konversi otomatis link Google Maps biasa ke format embed
    if (preg_match('/^https:\/\/maps\.app\.goo\.gl\//', $link)) {
        // Ambil ID dari link
        $id = substr($link, strrpos($link, '/') + 1);
        // Format embed standar (tidak selalu akurat, user tetap disarankan pakai link embed)
        $link = 'https://www.google.com/maps/embed?pb=' . $id;
    }
    file_put_contents(base_path('resources/lokasi.txt'), $link);
    return back()->with('success', 'Lokasi berhasil diupdate!');
})->name('update-lokasi');


// Route untuk update Tentang Kami
Route::post('/admin/setting/update-tentang-kami', function (\Illuminate\Http\Request $request) {
    // Simpan data tentang_kami ke file atau database
    $isi = $request->input('tentang_kami');
    file_put_contents(base_path('resources/tentang_kami.txt'), $isi);
    return back()->with('success', 'Tentang Kami berhasil diupdate!');
})->name('update-tentang-kami');


// Route untuk update foto home
Route::post('/admin/setting/update-foto-home', function (\Illuminate\Http\Request $request) {
    if ($request->hasFile('foto_home')) {
        $file = $request->file('foto_home');
        $file->move(public_path('images'), 'cafe.jpg');
    }
    return back()->with('success', 'Foto home berhasil diupdate!');
})->name('update-foto-home');


// Route dinamis untuk update foto slide 1-5
Route::post('/admin/setting/update-foto-slide/{slide}', function ($slide, \Illuminate\Http\Request $request) {
    if ($request->hasFile('foto_slide')) {
        $file = $request->file('foto_slide');
        $slideNum = (int) $slide;
        if ($slideNum >= 1 && $slideNum <= 5) {
            $file->move(public_path('images'), 'slide' . $slideNum . '.jpg');
        }
    }
    return back()->with('success', 'Foto Slide ' . $slide . ' berhasil diupdate!');
})->name('update-foto-slide');

// Route dinamis untuk update foto ruangan indoor dan outdoor
Route::post('/admin/setting/update-foto-ruangan/{ruangan}', function ($slide, \Illuminate\Http\Request $request) {
    // dd($request->all());
    if ($request->hasFile('foto_ruangan')) {
        $file = $request->file('foto_ruangan');
        $slideNum = (int) $slide;
        if ($slideNum >= 1 && $slideNum <= 2) {
            $file->move(public_path('images'), 'ruangan' . $slideNum . '.jpg');
        }
    }
    return back()->with('success', 'Foto ruangan ' . $slide === 1 ? 'Indoor' : 'Outdoor' . ' berhasil diupdate!');
})->name('update-foto-ruangan');


// Route untuk update foto Tentang Kami
Route::post('/admin/setting/update-foto-tentang', function (\Illuminate\Http\Request $request) {
    if ($request->hasFile('foto_tentang')) {
        $file = $request->file('foto_tentang');
        $file->move(public_path('images'), 'foto_tentang.jpg');
    }
    return back()->with('success', 'Foto Tentang Kami berhasil diupdate!');
})->name('update-foto-tentang');

// Route untuk update QRIS QR Code
Route::post('/admin/setting/update-qris', function (\Illuminate\Http\Request $request) {
    if ($request->hasFile('qris_image')) {
        $file = $request->file('qris_image');
        
        // Validasi file
        $request->validate([
            'qris_image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ], [
            'qris_image.required' => 'File QR Code QRIS harus diupload',
            'qris_image.image' => 'File harus berupa gambar',
            'qris_image.mimes' => 'Format gambar harus PNG, JPG, atau JPEG',
            'qris_image.max' => 'Ukuran file maksimal 2MB'
        ]);
        
        $file->move(public_path('images'), 'qrcode.png');
        return back()->with('success', 'QR Code QRIS berhasil diupdate!');
    }
    return back()->with('error', 'Tidak ada file yang diupload!');
})->name('update-qris');


// // webhok xendit
Route::post('/xendit/callback', [XenditCallbackController::class, 'handleCallback']);


// HAPUS SESSION
Route::post('/reset-session', [reservasiController::class, 'resetSession'])->name('reset.session');





// DATA USER
// Route::get('/admin/data-user', [homeAdminController::class, 'dataUser'])->name('dataUser');
//
// Route::get('/admin/datauser', function () {
//     return view('admin.datauser');
// })->name('admin.datauser');



