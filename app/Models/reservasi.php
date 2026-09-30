<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reservasi extends Model
{
    protected $table = 'reservasi';

    protected $primaryKey = 'id_reservasi';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_pelanggan',
        'tanggal',
        'waktu',
        'jumlah_tamu',
        'ruangan',
        'bukti_pembayaran',
        'status_kehadiran',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(
            pelangganModel::class,
            'id_pelanggan',
            'id_pelanggan'
        );
    }

    public function pengelola()
    {
        return $this->belongsTo(
            PengelolaModel::class,
            'id_pengelola',
            'id_pengelola'
        );
    }

    public function meja()
    {
        return $this->belongsToMany(
            Meja::class,
            'reservasi_meja',
            'id_reservasi',
            'id_meja'
        );
    }
}