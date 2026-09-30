<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('detail_pesanan') || Schema::hasColumn('detail_pesanan', 'qty_stok_dipotong')) {
            return;
        }

        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->unsignedInteger('qty_stok_dipotong')->default(0);
        });

        // Data yang sudah berstatus DP/lunas berarti stok lamanya sudah pernah dipotong
        // oleh callback versi sebelumnya. Backfill ini mencegah stok dipotong ulang.
        $paidTransactionIds = DB::table('transaksi')
            ->whereIn(DB::raw('LOWER(status)'), ['tervalidasi', 'selesai', 'lunas'])
            ->pluck('id_transaksi');

        if ($paidTransactionIds->isNotEmpty()) {
            DB::table('detail_pesanan')
                ->whereIn('id_transaksi', $paidTransactionIds)
                ->update(['qty_stok_dipotong' => DB::raw('qty')]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('detail_pesanan') && Schema::hasColumn('detail_pesanan', 'qty_stok_dipotong')) {
            Schema::table('detail_pesanan', function (Blueprint $table) {
                $table->dropColumn('qty_stok_dipotong');
            });
        }
    }
};
