<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Data;
use App\Models\Desa;
use App\Models\Kategory;

class MapControllerTest extends TestCase
{
    use RefreshDatabase; // Membersihkan database setelah setiap test

    public function test_map_index_returns_correct_data()
    {
        // Buat dummy desa
        $desa = Desa::factory()->create([
            'nama' => 'Desa Test',
        ]);

        // Buat dummy kategori
        $kategori = Kategory::factory()->create([
            'jenis' => 'Seni Budaya',
        ]);

        // Buat data yang menghubungkan desa dengan kategori
        $data = Data::factory()->create([
            'desa_id' => $desa->id,
            'kategory_id' => $kategori->id,
        ]);

        // Panggil route
        $response = $this->get(route('map'));

        // Pastikan HTTP response sukses
        $response->assertStatus(200);

        // Pastikan view menerima data desa yang benar
        $response->assertViewHas('data', function ($dataCollection) use ($desa, $kategori) {
            $firstData = $dataCollection->first();
            return $firstData->desa->nama === 'Desa Test' &&
                   $firstData->desa->longitude === 110.12345 &&
                   $firstData->desa->latitude === -7.12345 &&
                   $firstData->kategory->jenis === 'Seni Budaya';
        });
    }
}
