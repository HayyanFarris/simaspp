<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranSpp extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_spp';

    protected $fillable = [
        'siswa_id',
        'bulan',
        'tahun_ajaran',
        'jumlah_dibayar',
        'tanggal_bayar',
        'status',
        'dicatat_oleh',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function dicatatOleh()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
