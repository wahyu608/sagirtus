<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Memperbarui password pengguna yang sedang login.
     *
     * @param Request $request Objek request yang berisi data input dari pengguna
     * @return RedirectResponse Redirect kembali ke halaman sebelumnya dengan status pembaruan
     */
    public function update(Request $request): RedirectResponse
    {
        // Validasi input dari pengguna dengan menempatkan error di 'updatePassword'
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => [
                'required',        // Password lama harus diisi
                'current_password' // Pastikan password yang dimasukkan sesuai dengan yang tersimpan di database
            ],
            'password' => [
                'required',             // Password baru harus diisi
                Password::defaults(),   // Menggunakan aturan keamanan Laravel (minimal 8 karakter, kombinasi huruf, angka, dll.)
                'confirmed'             // Password baru harus sama dengan konfirmasi password
            ],
        ]);

        // Update password pengguna dengan meng-hash password baru sebelum disimpan
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect kembali dengan pesan sukses
        return back()->with('status', 'password-updated');
    }
}
