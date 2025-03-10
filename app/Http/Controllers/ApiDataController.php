<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;

class ApiDataController extends Controller
{
    public function getData()
    {
        // Ambil data dengan relasi ke desa dan kategori
        $data = Data::with(['desa:id,nama', 'kategory:id,jenis'])->get();

        // Kembalikan data dalam format JSON
        return response()->json(['data' => $data], 200);
    }
}