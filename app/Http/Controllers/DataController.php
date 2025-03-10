<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Desa;
use App\Models\Kategory;
use Illuminate\Http\Request;

class DataController extends Controller
{
    // Method untuk menampilkan data di halaman admin
    public function index( Request $request)
    {   
        // Mengambil data dari tabel Data dengan relasi ke Desa dan Kategori
        $data = Data::with([
                'desa:id,nama', // Mengambil nama desa berdasarkan desa_id
                'kategory:id,jenis', // Mengambil jenis kategori berdasarkan kategory_id
            ])->get();

        // Mengambil daftar desa untuk keperluan dropdown atau filtering
        $desas = Desa::select('id', 'nama')->get();
        // Mengambil daftar kategori untuk keperluan dropdown atau filtering
        $kategories = Kategory::select('id', 'jenis')->get();
        
        // Mengembalikan tampilan admin.data dengan data yang sudah diambil
        return view('admin.data', compact('data', 'desas', 'kategories'));
    }

    // Method untuk menyimpan data baru ke dalam database
    public function store(Request $request)
    {
        try {
            // Validasi input yang dikirim oleh pengguna
            $validatedData = $request->validate([
                'nama_kegiatan' => 'required|string|max:255',
                'deskripsi' => 'required',
                'desa_id' => 'required',
                'kategory_id' => 'required',
            ]);

            // Menyimpan data baru ke dalam database
            $data = Data::create($validatedData);

            // Mengembalikan respon JSON jika berhasil
            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            // Mengembalikan respon JSON jika terjadi kesalahan
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Method untuk menampilkan data spesifik berdasarkan ID (belum diimplementasikan)
    public function show()
    {
        // Method ini masih kosong, bisa diisi jika diperlukan
    }

    // Method untuk mengupdate data berdasarkan ID
    public function update(Request $request, $id)
    {
        // Mengambil data JSON yang dikirimkan oleh pengguna
        $data = $request->json()->all();
        
        // Validasi data sebelum diperbarui
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'desa_id' => 'required',
            'kategory_id' => 'required',
            'deskripsi' => 'required',
        ]);
        
        // Mencari record berdasarkan ID
        $record = Data::find($id);
        if ($record) {
            // Mengupdate data yang ditemukan dengan data baru
            $record->nama_kegiatan = $data['nama_kegiatan'];
            $record->deskripsi = $data['deskripsi'];
            $record->desa_id = $data['desa_id'];
            $record->kategory_id = $data['kategory_id'];
            
            // Menyimpan perubahan ke dalam database
            $record->save();

            // Mengembalikan respon JSON jika berhasil
            return response()->json(['status' => 'success', 'data' => $record]);
        } else {
            // Mengembalikan respon JSON jika data tidak ditemukan
            return response()->json(['status' => 'error', 'message' => 'Record not found'], 404);
        }
    }
    
    // Method untuk menghapus data berdasarkan ID
    public function destroy($id)
    {
        // Mencari data berdasarkan ID
        $record = Data::find($id);
        if ($record) {
            // Menghapus data jika ditemukan
            $record->delete();
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil di Hapus']);
        } else {
            // Mengembalikan respon JSON jika data tidak ditemukan
            return response()->json(['status' => 'error', 'message' => 'data tidak ditemukan'], 404);
        }
    }
    public function dataJson() {
        $data = Data::select('id', 'nama_kegiatan', 'deskripsi', 'lokasi', 'desa_id', 'kategory_id')
        ->with([
            'desa:id,nama',
            'kategory:id,jenis',
        ])->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
