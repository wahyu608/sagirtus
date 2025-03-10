<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan untuk melakukan request ini.
     */
    public function authorize(): bool
    {
        return true; // Mengizinkan semua pengguna untuk mengakses request ini.
    }

    /**
     * Mendapatkan aturan validasi yang berlaku untuk request ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'], // Email wajib diisi dan harus valid
            'password' => ['required', 'string'], // Password wajib diisi
        ];
    }

    /**
     * Mencoba mengautentikasi kredensial pengguna.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // Pastikan permintaan login tidak melebihi batas rate limit
        $this->ensureIsNotRateLimited();

        // Mencoba autentikasi dengan kredensial email dan password
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) 
        {
            // Jika gagal, tambahkan ke rate limiter
            RateLimiter::hit($this->throttleKey());

            // Kembalikan pesan error bahwa login gagal
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Jika autentikasi berhasil, bersihkan rate limiter untuk pengguna ini
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Memastikan bahwa request login tidak melebihi batas rate limit.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        // Cek apakah pengguna telah melebihi batas percobaan login (misalnya 5 kali)
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return; // Jika belum melebihi, lanjutkan proses login
        }

        // Jika melebihi batas, trigger event Lockout
        event(new Lockout($this));

        // Hitung waktu tunggu sebelum bisa mencoba login lagi
        $seconds = RateLimiter::availableIn($this->throttleKey());

        // Lemparkan error dengan pesan throttle
        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Mendapatkan kunci throttle untuk membatasi percobaan login.
     */
    public function throttleKey(): string
    {
        // Menggunakan kombinasi email (dalam lowercase) dan IP pengguna untuk membuat kunci unik
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
