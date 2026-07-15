<?php

namespace Database\Factories;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nis' => 'NIS-' . $this->faker->unique()->numberBetween(1000, 9999),
            'nama' => $this->faker->name(),
            'kelas_id' => Kelas::factory(),
            'nominal_spp' => $this->faker->randomElement([100000, 150000, 200000, 250000]),
            'nama_orang_tua' => $this->faker->name(),
            'no_telepon_orang_tua' => $this->faker->phoneNumber(),
        ];
    }
}
