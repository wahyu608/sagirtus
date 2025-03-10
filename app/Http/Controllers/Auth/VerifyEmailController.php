<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Menandai alamat email pengguna yang telah diautentikasi sebagai terverifikasi.
     *
     * @param EmailVerificationRequest $request Objek request yang menangani verifikasi email.
     * @return RedirectResponse Redirect ke halaman dashboard setelah verifikasi email berhasil.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Jika email sudah terverifikasi sebelumnya, langsung redirect ke dashboard dengan status verified.
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        // Jika email berhasil diverifikasi, trigger event Verified untuk menangani tindakan setelah verifikasi.
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Redirect ke dashboard dengan query string `verified=1` untuk menandakan verifikasi berhasil.
        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
