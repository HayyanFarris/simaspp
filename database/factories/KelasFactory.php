<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_kelas' => $this->faker->randomElement(['7A', '7B', '8A', '8B', '9A', '9B']),
            'tingkat' => $this->faker->randomElement(['7', '8', '9']),
            'wali_kelas_id' => User::factory()->guru()->create()->id,
        ];
    }
}
