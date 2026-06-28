<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $primaryKey = 'id_detail_pesanan';

    public $incrementing = false; // karena id di-generate manual
    protected $keyType = 'int';

    protected $fillable = [
        'id_detail_pesanan',
        'id_transaksi',
        'id_menu',
        'qty',
        'catatan',
    ];

    /**
     * Relasi ke tabel transaksi
     * detail_pesanan -> transaksi (many to one)
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function menu()
    {
        return $this->belongsTo(MenuModel::class, 'id_menu', 'id_menu');
    }
}
