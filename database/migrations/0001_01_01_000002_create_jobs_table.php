<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengelola', function (Blueprint $table) {
            $table->bigIncrements('id_pengelola');
            $table->string('nama_pengelola', 50);
            $table->string('username', 25);
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('pelanggan', function (Blueprint $table) {
            $table->bigIncrements('id_pelanggan');
            $table->string('nama_pelanggan', 50);
            $table->string('username', 25);
            $table->string('password');
            $table->string('no_telepon', 15);
            $table->timestamps();
        });

        Schema::create('reservasi', function (Blueprint $table) {
            $table->bigIncrements('id_reservasi');
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            // $table->unsignedBigInteger('id_pengelola')->nullable();

            $table->date('tanggal');
            $table->time('waktu');
            $table->integer('jumlah_tamu');
            $table->text('ruangan');
            // $table->text('nomor_meja');
            $table->timestamps();
            $table->text('bukti_pembayaran');


            $table->foreign('id_pelanggan')
                ->references('id_pelanggan')
                ->on('pelanggan');

           
        });

        Schema::create('menu', function (Blueprint $table) {
            $table->bigIncrements('id_menu');
            $table->unsignedBigInteger('id_pengelola');
            $table->string('nama_menu', 50);
            $table->string('kategori', 25);
            $table->integer('harga');
            $table->text('jenis');
            $table->string('deskripsi');
            $table->string('gambar');
            $table->integer('stok');
            $table->timestamps();

            $table->foreign('id_pengelola')
                ->references('id_pengelola')
                ->on('pengelola');
        });

        Schema::create('transaksi', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi');
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            $table->unsignedBigInteger('id_pengelola')->nullable();
            $table->unsignedBigInteger('id_reservasi')->nullable();

            $table->integer('total');
            $table->string('metode_pembayaran', 25);
            $table->string('status', 25);
            $table->text('checkout_url');
            $table->timestamps();

            $table->foreign('id_pelanggan')
                ->references('id_pelanggan')
                ->on('pelanggan')
                ->cascadeOnDelete();

            $table->foreign('id_pengelola')
                ->references('id_pengelola')
                ->on('pengelola')
                ->nullOnDelete();

            $table->foreign('id_reservasi')
                ->references('id_reservasi')
                ->on('reservasi')
                ->cascadeOnDelete();
        });


        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->bigIncrements('id_detail_pesanan');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_menu');
            $table->integer('qty');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_transaksi')
                ->references('id_transaksi')
                ->on('transaksi');

            $table->foreign('id_menu')
                ->references('id_menu')
                ->on('menu');
        });


        Schema::create('meja', function (Blueprint $table) {
            $table->bigIncrements('id_meja');
            $table->text('kode_meja'); // A1, A2
            $table->text('ruangan');
            $table->timestamps();
        });


        // PIVOT TABEL RESERVASI_MEJA
        Schema::create('reservasi_meja', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_reservasi');
            $table->unsignedBigInteger('id_meja');

            $table->foreign('id_reservasi')
                ->references('id_reservasi')
                ->on('reservasi')
                ->cascadeOnDelete();

            $table->foreign('id_meja')
                ->references('id_meja')
                ->on('meja')
                ->cascadeOnDelete();
        });
    }

   public function down(): void
    {
        // 1. Hapus tabel pivot / tabel anak yang bergantung pada tabel lain terlebih dahulu
        Schema::dropIfExists('reservasi_meja');
        Schema::dropIfExists('detail_pesanan');
        
        // 2. Hapus transaksi karena bergantung pada reservasi, pelanggan, dan pengelola
        Schema::dropIfExists('transaksi');
        
        // 3. Hapus reservasi dan menu
        Schema::dropIfExists('reservasi');
        Schema::dropIfExists('menu');
        
        // 4. Terakhir, aman untuk menghapus tabel induk utama
        Schema::dropIfExists('meja');
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('pengelola');
    }
};
