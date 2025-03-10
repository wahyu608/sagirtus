<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Data;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Data::factory(10)->create([     
            'kategory_id' => 1,
            'desa_id' => 1,
            'nama_kegiatan' => 'Kegiatan 1',
            'lokasi' => 'Lokasi 1',
            'deskripsi' => 'Deskripsi 1',
        ]);
    }
}
