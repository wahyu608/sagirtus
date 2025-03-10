<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategory;

class KategoryController extends Controller
{
    // Method untuk menampilkan daftar kategori
    public function index()
    {
        // Mengambil semua data kategori dari database
        $kategories = Kategory::all();

        // Mengembalikan tampilan admin.kategory dengan data kategori
        return view('admin.kategory', compact('kategories'));
    }

    // Method untuk menyimpan data kategori baru
    public function store(Request $request)
    {
        try {
            // Validasi input dari request
            $validateData = $request->validate([
                'jenis' => 'required', // Field jenis harus diisi
            ]);
            
            // Menyimpan data kategori ke dalam database
            $jenis = Kategory::create($validateData);

            // Mengembalikan respons JSON sukses
            return response()->json(['status' => 'success', 'jenis' => $jenis], 201);
        } catch (\Exception $e) {
            // Menangani error jika terjadi kesalahan
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Method untuk memperbarui data kategori
    public function update(Request $request, $id)
    {
        // Mengambil data JSON dari request
        $data = $request->json()->all();
        
        // Validasi data
        $request->validate([
            'jenis' => 'required', // Field jenis harus diisi
        ]);
        
        // Mencari kategori berdasarkan ID
        $record = Kategory::find($id);
        if ($record) {
            // Memperbarui data kategori
            $record->jenis = $data['jenis'];
            $record->save();

            return response()->json(['status' => 'success', 'data' => $record]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Record not found'], 404);
        }
    }

    // Method untuk menghapus data kategori
    public function destroy($id)
    {
        // Mencari kategori berdasarkan ID
        $record = Kategory::find($id);
        if ($record) {
            $record->delete();
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil di Hapus']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }
    }
    public function kategoryJson() {
        $data = Kategory::select('id', 'jenis')
        ->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }   
    
}
