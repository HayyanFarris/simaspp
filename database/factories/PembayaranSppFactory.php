<?php

namespace Database\Factories;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PembayaranSpp>
 */
class PembayaranSppFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement(['lunas', 'belum_lunas', 'cicil']);

        return [
            'siswa_id' => Siswa::factory(),
            'bulan' => $this->faker->randomElement([
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ]),
            'tahun_ajaran' => $this->faker->randomElement(['2024/2025', '2025/2026']),
            'jumlah_dibayar' => $this->faker->randomFloat(2, 50000, 250000),
            'tanggal_bayar' => $this->faker->date(),
            'status' => $status,
            'dicatat_oleh' => User::factory()->guru()->create()->id,
            'keterangan' => $status === 'cicil' ? 'Cicilan ke-1 dari 2' : null,
        ];
    }
}
