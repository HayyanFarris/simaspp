<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kelas_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi: user (guru) memiliki satu kelas yang diampu
    public function kelasDiampu()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    // Relasi: user (guru) mencatat banyak pembayaran
    public function pembayaranDicatat()
    {
        return $this->hasMany(PembayaranSpp::class, 'dicatat_oleh');
    }

    // Helper cek role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }
}
