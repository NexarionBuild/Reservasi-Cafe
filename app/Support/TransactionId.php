<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TransactionId
{
    /**
     * Format: YYYYMMDDNNNN, contoh 202609080001.
     * Urutan di-reset setiap hari dan dibuat di dalam transaction/lock agar tidak bentrok.
     */
    public static function next(): string
    {
        $prefix = Carbon::now()->format('Ymd');

        $lastId = DB::table('transaksi')
            ->whereRaw('CAST(id_transaksi AS TEXT) LIKE ?', [$prefix . '%'])
            ->lockForUpdate()
            ->orderByDesc('id_transaksi')
            ->value('id_transaksi');

        $sequence = $lastId ? ((int) substr((string) $lastId, 8)) + 1 : 1;

        return $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
