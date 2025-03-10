<?php

namespace App\Http\Controllers;

use App\Models\Kategory;
use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\Desa;

class MapController extends Controller
{
    // Method untuk mengambil data desa dan kegiatan untuk ditampilkan di peta
    public function data() {
        // Mengambil data desa dengan ID, nama, longitude, dan latitude
        $desaData = Desa::select('id', 'nama', 'longitude', 'latitude')->get();
        
        // Mengambil data kegiatan dengan relasi desa dan kategori
        $data = Data::select('id', 'desa_id', 'kategory_id')
            ->with([
                'desa:id,nama,longitude,latitude', // Mengambil informasi desa terkait
                'kategory:id,jenis' // Mengambil informasi kategori terkait
            ])
            ->get(); 
    
        // Mengembalikan tampilan dashboard admin dengan data desa dan kegiatan
        return view('admin.dashboard', compact('desaData', 'data'));
    }
}
