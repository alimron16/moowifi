<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function notice(Request $request)
    {
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return $request->user()->isSuperAdmin()
                ? redirect()->route('super-admin.dashboard')
                : redirect()->route('tenant.dashboard');
        }

        return view('auth.verify-email');
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('error', 'Tautan verifikasi email tidak valid atau sudah kadaluarsa.');
        }

        if ($user->hasVerifiedEmail()) {
            if (!Auth::check()) {
                Auth::login($user);
            }
            return redirect()->route('tenant.dashboard')->with('success', 'Email Anda sudah terverifikasi sebelumnya.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        if (!Auth::check()) {
            Auth::login($user);
        }

        return redirect()->route('tenant.dashboard')->with('success', 'Selamat! Email berhasil diverifikasi. Akun MooWiFi Anda kini aktif penuh.');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('tenant.dashboard');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('success', 'Tautan verifikasi baru telah dikirimkan ke alamat email Anda.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengirim email verifikasi: ' . $e->getMessage() . '. Pastikan pengaturan SMTP Gmail sudah dikonfigurasi.');
        }
    }

    public function simulate(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->markEmailAsVerified();
            event(new Verified($user));
            return redirect()->route('tenant.dashboard')->with('success', 'Email berhasil diverifikasi (Mode Uji Coba Cepat)! Selamat datang di MooWiFi.');
        }

        return redirect()->route('login');
    }
}
