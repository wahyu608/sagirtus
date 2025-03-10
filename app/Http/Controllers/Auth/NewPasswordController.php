<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Menampilkan halaman reset password kepada pengguna.
     *
     * @param Request $request Objek request yang berisi data dari URL (termasuk token reset password)
     * @return View Halaman reset password
     */
    public function create(Request $request): View
    {
        // Mengembalikan tampilan halaman reset password dengan membawa request yang berisi token
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Menangani permintaan reset password baru.
     *
     * @param Request $request Objek request yang berisi input dari pengguna
     * @return RedirectResponse Redirect ke halaman login jika sukses atau kembali ke halaman sebelumnya jika gagal
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input yang dikirimkan oleh pengguna
        $request->validate([
            'token' => ['required'], // Token reset password harus ada dalam request
            'email' => ['required', 'email'], // Email wajib diisi dan harus valid
            'password' => [
                'required',           // Password tidak boleh kosong
                'confirmed',          // Password harus sesuai dengan konfirmasi password
                Rules\Password::defaults(), // Menggunakan aturan default Laravel untuk password yang aman
            ],
        ]);

        // Proses reset password menggunakan Laravel Password Broker
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'), // Mengambil input yang diperlukan
            function ($user) use ($request) { // Callback jika proses reset berhasil
                // Update password baru dengan cara di-hash sebelum disimpan ke database
                $user->forceFill([
                    'password' => Hash::make($request->password), // Hash password agar aman
                    'remember_token' => Str::random(60), // Generate token baru untuk sesi user
                ])->save(); // Simpan perubahan ke database

                // Event bawaan Laravel yang menandakan password user telah diperbarui
                event(new PasswordReset($user));
            }
        );

        // Cek apakah proses reset password berhasil atau gagal
        if ($status == Password::PASSWORD_RESET) {
            // Jika berhasil, redirect ke halaman login dengan pesan sukses
            return redirect()->route('login')->with('status', __($status));
        } else {
            // Jika gagal, kembalikan ke halaman sebelumnya dengan pesan error
            return back()->withInput($request->only('email')) // Tetap menyertakan email yang dimasukkan
                ->withErrors(['email' => __($status)]); // Tampilkan error sesuai status yang diberikan Laravel
        }
    }
}
