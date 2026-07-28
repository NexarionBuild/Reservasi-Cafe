<?php

namespace Database\Seeders;

use App\Models\MenuModel;
use App\Models\pelangganModel;
use App\Models\PengelolaModel;
use App\Models\Meja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Container\Attributes\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Symfony\Component\Clock\now;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // data pengelola dummy
        $pengelolaData = [
            [
                // 'id_pengelola' => 1,
                'nama_pengelola' => 'admin',
                'username' => 'admin',
                'password' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // data pelanggan dummy
        $pelangganData = [
            [
                // 'id_pelanggan' => 1,
                'nama_pelanggan' => 'budi',
                'username' => 'budi',
                'password' => 'budi',
                'no_telepon' => '6289',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // // data pelanggan dummy
        // $Meja = [
        //     [
        //         'kode_meja' => 'IM1',
        //         'ruangan' => 'Indoor',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        // ];

        // // Data dummy untuk tabel 'menu'
        // $menuData = [

        //     //MAKANAN 

        //     [
        //         'id_pengelola' => 1, // Pastikan ID pengelola ini ada di tabel 'pengelola'
        //         'nama_menu' => 'Nasi Goreng Spesial',
        //         'kategori' => 'makanan',
        //         'harga' => 25000,
        //         'deskripsi' => 'Nasi yang digoreng dengan bumbu rempah spesial, telur, dan irisan ayam.',
        //         'gambar' => 'menu/makanan-1.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Ayam Geprek Sambal Matah',
        //         'kategori' => 'makanan',
        //         'harga' => 22000,
        //         'deskripsi' => 'Ayam goreng renyah yang digeprek dengan sambal matah segar.',
        //         'gambar' => 'menu/makanan-3.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1, // Pastikan ID pengelola ini ada di tabel 'pengelola'
        //         'nama_menu' => 'Nasi Goreng Spesial',
        //         'kategori' => 'makanan',
        //         'harga' => 25000,
        //         'deskripsi' => 'Nasi yang digoreng dengan bumbu rempah spesial, telur, dan irisan ayam.',
        //         'gambar' => 'menu/makanan-4.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Ayam Geprek Sambal Matah',
        //         'kategori' => 'makanan',
        //         'harga' => 22000,
        //         'deskripsi' => 'Ayam goreng renyah yang digeprek dengan sambal matah segar.',
        //         'gambar' => 'menu/makanan-5.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1, // Pastikan ID pengelola ini ada di tabel 'pengelola'
        //         'nama_menu' => 'Nasi Goreng Spesial',
        //         'kategori' => 'makanan',
        //         'harga' => 25000,
        //         'deskripsi' => 'Nasi yang digoreng dengan bumbu rempah spesial, telur, dan irisan ayam.',
        //         'gambar' => 'menu/makanan-6.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Ayam Geprek Sambal Matah',
        //         'kategori' => 'makanan',
        //         'harga' => 22000,
        //         'deskripsi' => 'Ayam goreng renyah yang digeprek dengan sambal matah segar.',
        //         'gambar' => 'menu/makanan-7.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Ayam Geprek Sambal Matah',
        //         'kategori' => 'makanan',
        //         'harga' => 22000,
        //         'deskripsi' => 'Ayam goreng renyah yang digeprek dengan sambal matah segar.',
        //         'gambar' => 'menu/makanan-8.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Ayam Geprek Sambal Matah',
        //         'kategori' => 'makanan',
        //         'harga' => 22000,
        //         'deskripsi' => 'Ayam goreng renyah yang digeprek dengan sambal matah segar.',
        //         'gambar' => 'menu/makanan-9.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Ayam Geprek Sambal Matah',
        //         'kategori' => 'makanan',
        //         'harga' => 22000,
        //         'deskripsi' => 'Ayam goreng renyah yang digeprek dengan sambal matah segar.',
        //         'gambar' => 'menu/makanan-10.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],

        //     // MINUMAN
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Jus Jeruk',
        //         'kategori' => 'minuman',
        //         'harga' => 18000,
        //         'deskripsi' => 'Paduan kopi, susu creamy, dan gula aren otentik.',
        //         'gambar' => 'menu/minuman-1.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Es Kopi Susu Aren',
        //         'kategori' => 'minuman',
        //         'harga' => 18000,
        //         'deskripsi' => 'Paduan kopi, susu creamy, dan gula aren otentik.',
        //         'gambar' => 'menu/minuman-3.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Jus Jeruk',
        //         'kategori' => 'minuman',
        //         'harga' => 18000,
        //         'deskripsi' => 'Paduan kopi, susu creamy, dan gula aren otentik.',
        //         'gambar' => 'menu/minuman-4.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Es Kopi Susu Aren',
        //         'kategori' => 'minuman',
        //         'harga' => 18000,
        //         'deskripsi' => 'Paduan kopi, susu creamy, dan gula aren otentik.',
        //         'gambar' => 'menu/minuman-5.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Jus Jeruk',
        //         'kategori' => 'minuman',
        //         'harga' => 18000,
        //         'deskripsi' => 'Paduan kopi, susu creamy, dan gula aren otentik.',
        //         'gambar' => 'menu/minuman-6.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Es Kopi Susu Aren',
        //         'kategori' => 'minuman',
        //         'harga' => 18000,
        //         'deskripsi' => 'Paduan kopi, susu creamy, dan gula aren otentik.',
        //         'gambar' => 'menu/minuman-7.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Jus Jeruk',
        //         'kategori' => 'minuman',
        //         'harga' => 18000,
        //         'deskripsi' => 'Paduan kopi, susu creamy, dan gula aren otentik.',
        //         'gambar' => 'menu/minuman-8.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Es Kopi Susu Aren',
        //         'kategori' => 'minuman',
        //         'harga' => 18000,
        //         'deskripsi' => 'Paduan kopi, susu creamy, dan gula aren otentik.',
        //         'gambar' => 'menu/minuman-9.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'id_pengelola' => 1,
        //         'nama_menu' => 'Es Kopi Susu Aren',
        //         'kategori' => 'minuman',
        //         'harga' => 18000,
        //         'deskripsi' => 'Paduan kopi, susu creamy, dan gula aren otentik.',
        //         'gambar' => 'menu/minuman-10.jpg',
        //         'stok' => 10,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        // ];

        
        PengelolaModel::insert($pengelolaData);
        pelangganModel::insert($pelangganData);
        // MenuModel::insert($menuData);
        // Meja::insert($Meja);
    }
}
