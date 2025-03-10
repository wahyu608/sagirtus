<?php

namespace Database\Seeders;

use App\Models\Kategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategory::create([
            'jenis' => 'Kegiatan 1',
        ]);
        Kategory::create([
            'jenis' => 'Kegiatan 1',
        ]);
    }
}
