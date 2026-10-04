<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SaasPlan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        if (Auth::check()) {
            return Auth::user()->isSuperAdmin()
                ? redirect()->route('super-admin.dashboard')
                : redirect()->route('tenant.dashboard');
        }

        $plans = SaasPlan::where('is_active', true)->get();
        $selectedPlanCode = strtoupper($request->query('plan', ''));
        $selectedPlanId = $request->query('plan_id');

        return view('auth.register', compact('plans', 'selectedPlanCode', 'selectedPlanId'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'tenant_name' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:25'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'saas_plan_id' => ['nullable', 'exists:saas_plans,id'],
        ], [
            'tenant_name.required' => 'Nama bisnis / RT/RW Net wajib diisi.',
            'name.required' => 'Nama lengkap penanggung jawab wajib diisi.',
            'email.required' => 'Alamat email aktif wajib diisi untuk verifikasi.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan masuk atau gunakan email lain.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = DB::transaction(function () use ($validated, $request) {
            $code = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $validated['tenant_name']), 0, 4));
            if (empty($code)) {
                $code = 'NET';
            }

            $tenant = Tenant::create([
                'name' => $validated['tenant_name'],
                'code' => $code . rand(10, 99),
                'slug' => Str::slug($validated['tenant_name']) . '-' . Str::lower(Str::random(4)),
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'status' => 'ACTIVE',
                'trial_ends_at' => now()->addDays(14),
            ]);

            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'OWNER',
                'status' => 'ACTIVE',
            ]);

            $planId = $validated['saas_plan_id'] ?? SaasPlan::where('is_active', true)->first()?->id;
            if ($planId) {
                Subscription::create([
                    'tenant_id' => $tenant->id,
                    'saas_plan_id' => $planId,
                    'status' => 'TRIAL',
                    'starts_at' => now(),
                    'ends_at' => now()->addDays(14),
                ]);
            }

            return $user;
        });

        Auth::login($user);

        // Send Email Verification
        try {
            event(new Registered($user));
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim email verifikasi saat registrasi: " . $e->getMessage());
        }

        return redirect()->route('verification.notice')->with('success', 'Pendaftaran berhasil! Tautan verifikasi telah dikirim ke email Anda. Silakan verifikasi untuk mulai mengelola RT/RW Net.');
    }
}
