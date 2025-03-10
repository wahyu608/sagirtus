<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\Desa;
use App\Models\Kategory;

class GuestController extends Controller
{
    // Method untuk menampilkan halaman dashboard pengguna
    public function index() {
        // Mengambil data semua desa dengan atribut id, nama, longitude, dan latitude
        $desaData = Desa::select('id', 'nama', 'longitude', 'latitude')->get();
        
        // Mengambil data kegiatan dengan relasi ke desa dan kategori
        $data = Data::select('id', 'desa_id', 'kategory_id')
            ->with([
                'desa:id,nama,longitude,latitude', // Mengambil informasi desa terkait
                'kategory:id,jenis' // Mengambil informasi kategori terkait
            ])
            ->get(); 
    
        // Mengembalikan tampilan users.dashboard dengan data yang diambil
        return view('users.dashboard', compact('desaData', 'data'));
    }

    // Method untuk menampilkan halaman data pengguna
    public function data()
    {
        // Mengambil data kegiatan dengan informasi desa dan kategori terkait
        $data = Data::select('id', 'nama_kegiatan','deskripsi', 'desa_id', 'kategory_id')
            ->with([
                'desa:id,nama', // Mengambil nama desa terkait
                'kategory:id,jenis' // Mengambil jenis kategori terkait
            ])
            ->get();

        // Mengambil daftar desa untuk ditampilkan di halaman
        $desas = Desa::select('id', 'nama')->get();
        
        // Mengambil daftar kategori untuk ditampilkan di halaman
        $kategories = Kategory::select('id', 'jenis')->get();

        // Mengembalikan tampilan users.data dengan data yang diambil
        return view('users.data', compact('data', 'desas', 'kategories'));
    }
}
