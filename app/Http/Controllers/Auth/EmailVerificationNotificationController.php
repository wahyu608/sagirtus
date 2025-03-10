<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Mengirim ulang email verifikasi kepada pengguna.
     */
    public function store(Request $request): RedirectResponse
    {
        // Jika pengguna sudah memverifikasi email, arahkan ke dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Mengirim ulang email verifikasi
        $request->user()->sendEmailVerificationNotification();

        // Redirect kembali ke halaman sebelumnya dengan status sukses
        return back()->with('status', 'verification-link-sent');
    }
}
