<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Menampilkan halaman konfirmasi password.
     *
     * Method ini akan mengembalikan tampilan form konfirmasi password,
     * yang biasanya digunakan sebelum melakukan tindakan sensitif
     * seperti mengubah pengaturan akun.
     */
    public function show(): View
    {
        return view('auth.confirm-password'); // Menampilkan view konfirmasi password
    }

    /**
     * Memvalidasi password pengguna sebelum melanjutkan ke tindakan berikutnya.
     *
     * @param Request $request Objek request yang berisi data yang dikirim oleh pengguna.
     * @return RedirectResponse Redirect ke halaman dashboard jika sukses.
     * @throws ValidationException Jika password salah.
     */
    public function store(Request $request): RedirectResponse
    {
        // Memeriksa apakah password yang dimasukkan benar berdasarkan email pengguna yang sedang login
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email, // Menggunakan email pengguna saat ini
            'password' => $request->password, // Password yang dimasukkan oleh pengguna
        ])) {
            // Jika password salah, lemparkan error validasi
            throw ValidationException::withMessages([
                'password' => __('auth.password'), // Menggunakan pesan error dari file bahasa
            ]);
        }

        // Jika password benar, simpan waktu konfirmasi password ke session
        $request->session()->put('auth.password_confirmed_at', time());

        // Redirect ke halaman yang dituju (dashboard)
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
