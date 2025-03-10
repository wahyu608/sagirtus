<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman registrasi pengguna.
     *
     * @return View Halaman yang berisi form pendaftaran pengguna.
     */
    public function create(): View
    {
        return view('auth.register'); // Menampilkan tampilan untuk registrasi pengguna.
    }

    /**
     * Menangani permintaan pendaftaran pengguna baru.
     *
     * @param Request $request Objek request yang berisi data input pengguna.
     * @return RedirectResponse Redirect ke dashboard setelah registrasi berhasil.
     * 
     * @throws \Illuminate\Validation\ValidationException Jika validasi gagal.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input pengguna sebelum membuat akun baru.
        $request->validate([
            'name' => ['required', 'string', 'max:255'], // Nama wajib diisi dan maksimal 255 karakter.
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                'unique:' . User::class // Email harus unik di tabel users.
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()], // Password harus dikonfirmasi.
        ]);

        // Membuat pengguna baru di database.
        $user = User::create([
            'name' => $request->name, // Menyimpan nama pengguna.
            'email' => $request->email, // Menyimpan email pengguna.
            'password' => Hash::make($request->password), // Hashing password sebelum menyimpannya.
        ]);

        // Memicu event bahwa pengguna baru telah terdaftar.
        event(new Registered($user));

        // Langsung login pengguna setelah registrasi.
        Auth::login($user);

        // Redirect ke halaman dashboard setelah berhasil login.
        return redirect(route('dashboard', absolute: false));
    }
}
