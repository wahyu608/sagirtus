<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Desa;

class DesaController extends Controller
{
    // Method untuk menampilkan semua data desa
    public function index()
    {    
        // Mengambil semua data desa dari database
        $desas = Desa::all();

        // Mengembalikan tampilan admin.desa dengan data yang diambil
        return view('admin.desa', compact('desas'));
    }

    // Method untuk menyimpan data desa baru ke dalam database
    public function store(Request $request)
    {
        try {
            // Validasi input dari pengguna
            $validateData = $request->validate([
                'nama' => 'required', // Nama desa wajib diisi
                'longitude' => 'required', // Longitude wajib diisi
                'latitude' => 'required', // Latitude wajib diisi
            ]);
            
            // Menyimpan data baru ke dalam database
            $desa = Desa::create($validateData);

            // Mengembalikan respon JSON jika berhasil
            return response()->json(['status' => 'success', 'desa' => $desa], 201);
        } catch (\Exception $e) {
            // Mengembalikan respon JSON jika terjadi kesalahan
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    // Method untuk mengupdate data desa berdasarkan ID
    public function update(Request $request, $id) 
    {
        // Mengambil data JSON yang dikirim oleh pengguna
        $data = $request->json()->all();
        
        // Validasi data sebelum diperbarui
        $request->validate([
            'nama' => 'required', // Nama desa wajib diisi
            'longitude' => 'required', // Longitude wajib diisi
            'latitude' => 'required', // Latitude wajib diisi
        ]);

        // Mencari desa berdasarkan ID
        $desa = Desa::find($id);
        if ($desa) {
            // Mengupdate data desa dengan data baru
            $desa->nama = $data['nama'];
            $desa->longitude = $data['longitude'];
            $desa->latitude = $data['latitude'];
            $desa->save();

            // Mengembalikan respon JSON jika berhasil
            return response()->json(['status' => 'success', 'desa' => $desa]);
        } else {
            // Mengembalikan respon JSON jika desa tidak ditemukan
            return response()->json(['status' => 'error', 'message' => 'Desa not found'], 404);
        }
    }
    
    // Method untuk menghapus data desa berdasarkan ID
    public function destroy($id)
    {
        // Mencari desa berdasarkan ID
        $record = Desa::find($id);
        if ($record) {
            // Menghapus desa jika ditemukan
            $record->delete();
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil di Hapus']);
        } else {
            // Mengembalikan respon JSON jika desa tidak ditemukan
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }
    }

    public function desaJson() {
        $data = Desa::select('id', 'nama', 'longitude', 'latitude')->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
