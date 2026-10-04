@extends('layouts.guest')

@section('title', 'Daftar Akun RT/RW Net Baru')

@section('container-class', 'container-register')

@section('guest-content')
<style>
    .container-register {
        max-width: 680px;
    }
    .register-card {
        border-radius: 20px;
        border: 1px solid rgba(0, 58, 67, 0.1);
        box-shadow: 0 12px 35px rgba(0, 58, 67, 0.07);
        overflow: hidden;
        background: #ffffff;
    }
    .register-card .card-body {
        padding: 2.25rem 1.75rem;
    }
    @media (min-width: 768px) {
        .register-card .card-body {
            padding: 3.25rem 3rem;
        }
    }
    .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        border-radius: 10px;
        padding: 0.65rem 0.95rem;
        font-size: 0.925rem;
        border-color: #cbd5e1;
    }
    .form-control:focus, .form-select:focus {
        border-color: #003A43;
        box-shadow: 0 0 0 0.2rem rgba(0, 58, 67, 0.15);
    }
    .input-icon .form-control {
        padding-left: 2.75rem;
    }
    .input-icon-addon {
        min-width: 2.75rem;
        color: #64748b;
    }
    .form-hint {
        margin-top: 0.4rem;
        font-size: 0.8rem;
    }
</style>

<div class="card register-card">
    <div class="card-body">
        <div class="text-center mb-4 pb-2 border-bottom">
            <h2 class="h2 mb-2 fw-bold text-dark">Mulai Coba Gratis 14 Hari</h2>
            <p class="text-secondary small mb-3">
                Daftarkan RT/RW Net atau ISP Anda sekarang dan nikmati kemudahan automasi billing &amp; MikroTik tanpa ribet.
            </p>
        </div>

        <form action="{{ route('register') }}" method="POST" autocomplete="off">
            @csrf

            <!-- Nama Bisnis / Brand -->
            <div class="mb-4">
                <label class="form-label required">Nama Bisnis / Brand RT/RW Net</label>
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <i class="ti ti-building-community fs-3"></i>
                    </span>
                    <input type="text" name="tenant_name" class="form-control @error('tenant_name') is-invalid @enderror" placeholder="Contoh: BintangNet, CitraWiFi" value="{{ old('tenant_name') }}" required autofocus>
                </div>
                <small class="form-hint text-muted">Nama brand ini yang akan tertera resmi di invoice, bukti bayar, dan WhatsApp pelanggan.</small>
            </div>

            <!-- Penanggung Jawab & WhatsApp -->
            <div class="row g-3 g-md-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label required">Nama Penanggung Jawab</label>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-user fs-3"></i>
                        </span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap Pemilik" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label required">Nomor WhatsApp Aktif</label>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-brand-whatsapp fs-3"></i>
                        </span>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="08123456789" value="{{ old('phone') }}" required>
                    </div>
                </div>
            </div>

            <!-- Email Aktif -->
            <div class="mb-4">
                <label class="form-label required">Alamat Email Bisnis Aktif</label>
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <i class="ti ti-mail fs-3"></i>
                    </span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="pemilik@domain.com" value="{{ old('email') }}" required>
                </div>
                <small class="form-hint text-primary"><i class="ti ti-info-circle me-1"></i> Tautan verifikasi akun resmi akan dikirimkan ke alamat email ini.</small>
            </div>

            <!-- Pilihan Paket SaaS -->
            <div class="mb-4">
                <label class="form-label required">Pilih Paket SaaS Awal</label>
                <select name="saas_plan_id" class="form-select @error('saas_plan_id') is-invalid @enderror" required>
                    @foreach($plans as $p)
                        <option value="{{ $p->id }}" {{ (old('saas_plan_id') == $p->id || $selectedPlanCode === $p->code || $selectedPlanId == $p->id) ? 'selected' : '' }}>
                            {{ $p->name }} — Rp {{ number_format($p->price, 0, ',', '.') }}/bulan (Kapasitas: {{ $p->max_customers == 0 ? 'Unlimited' : $p->max_customers }} Pelanggan, {{ $p->max_routers }} Router)
                        </option>
                    @endforeach
                </select>
                <small class="form-hint text-muted">Semua pendaftaran baru otomatis mendapatkan <strong>Trial Uji Coba Gratis 14 Hari</strong> tanpa biaya awal.</small>
            </div>

            <!-- Password & Konfirmasi -->
            <div class="row g-3 g-md-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label required">Kata Sandi Akun</label>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-lock fs-3"></i>
                        </span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label required">Ulangi Kata Sandi</label>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-lock-check fs-3"></i>
                        </span>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik ulang sandi" required>
                    </div>
                </div>
            </div>

            <!-- Persetujuan Ketentuan -->
            <div class="mb-4 pt-1">
                <label class="form-check">
                    <input type="checkbox" name="terms" class="form-check-input" required checked>
                    <span class="form-check-label small text-secondary">
                        Saya menyetujui <a href="#" class="text-decoration-none">Ketentuan Layanan</a> &amp; <a href="#" class="text-decoration-none">Kebijakan Privasi</a> platform MooWiFi.
                    </span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="form-footer mt-4">
                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold fs-3">
                    <i class="ti ti-user-plus me-2"></i> Buat Akun &amp; Mulai Coba Gratis
                </button>
            </div>
        </form>
    </div>

    <div class="card-footer text-center py-3.5 bg-body-tertiary border-top">
        <span class="text-secondary small">Sudah memiliki akun MooWiFi?</span>
        <a href="{{ route('login') }}" class="ms-1 fw-bold text-decoration-none text-primary">Masuk ke Dasbor</a>
    </div>
</div>
@endsection
