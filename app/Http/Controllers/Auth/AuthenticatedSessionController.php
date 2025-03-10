<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses permintaan autentikasi pengguna.
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentikasi user berdasarkan request yang dikirimkan
        $request->authenticate();

        // Regenerasi sesi untuk menghindari serangan session fixation
        $request->session()->regenerate();

        // Redirect ke halaman yang diinginkan setelah login
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Menghapus sesi pengguna yang sedang login.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout pengguna dari guard 'web'
        Auth::guard('web')->logout();

        // Invalidasi sesi saat ini untuk keamanan
        $request->session()->invalidate();

        // Regenerasi token CSRF untuk menghindari serangan CSRF
        $request->session()->regenerateToken();

        // Redirect ke halaman utama setelah logout
        return redirect('/');
    }
}
