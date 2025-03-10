<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Data>
 */
class DataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategory_id' => 1,
            'desa_id' => 1,
            'nama_kegiatan' => $this->faker->sentence,
            'lokasi' => $this->faker->sentence,
            'deskripsi' => $this->faker->sentence,
            
        ];
    }
}
