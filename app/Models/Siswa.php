<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nama',
        'kelas_id',
        'nominal_spp',
        'nama_orang_tua',
        'no_telepon_orang_tua',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranSpp::class);
    }
}
