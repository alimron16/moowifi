@extends('layouts.guest')

@section('title', 'Daftar Akun RT/RW Net Baru')

@section('guest-content')
<div class="card card-md shadow-sm">
    <div class="card-body">
        <h2 class="h2 text-center mb-1">Mulai Coba Gratis 14 Hari</h2>
        <p class="text-secondary text-center small mb-4">Daftarkan RT/RW Net atau ISP Anda sekarang dan nikmati automasi billing & MikroTik.</p>

        <form action="{{ route('register') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label class="form-label required">Nama Bisnis / Brand RT/RW Net</label>
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <i class="ti ti-building-community"></i>
                    </span>
                    <input type="text" name="tenant_name" class="form-control @error('tenant_name') is-invalid @enderror" placeholder="Contoh: BintangNet, CahayaWiFi" value="{{ old('tenant_name') }}" required autofocus>
                </div>
                <small class="form-hint">Nama ini akan tampil pada invoice dan payment link pelanggan Anda.</small>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-sm-6">
                    <label class="form-label required">Nama Penanggung Jawab</label>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-user"></i>
                        </span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap Anda" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label required">Nomor WhatsApp Aktif</label>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-brand-whatsapp"></i>
                        </span>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="08123456789" value="{{ old('phone') }}" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label required">Alamat Email Aktif</label>
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <i class="ti ti-mail"></i>
                    </span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="pemilik@domain.com" value="{{ old('email') }}" required>
                </div>
                <small class="form-hint text-primary"><i class="ti ti-info-circle me-1"></i> Tautan verifikasi akun akan dikirim ke email ini.</small>
            </div>

            <div class="mb-3">
                <label class="form-label required">Pilih Paket SaaS Awal</label>
                <select name="saas_plan_id" class="form-select @error('saas_plan_id') is-invalid @enderror" required>
                    @foreach($plans as $p)
                        <option value="{{ $p->id }}" {{ (old('saas_plan_id') == $p->id || $selectedPlanCode === $p->code || $selectedPlanId == $p->id) ? 'selected' : '' }}>
                            {{ $p->name }} - Rp {{ number_format($p->price, 0, ',', '.') }}/bln ({{ $p->max_customers == 0 ? 'Unlimited' : $p->max_customers }} Pelanggan, {{ $p->max_routers }} Router)
                        </option>
                    @endforeach
                </select>
                <small class="form-hint">Semua pendaftaran baru otomatis mendapatkan <strong>Trial Gratis 14 Hari</strong>.</small>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-sm-6">
                    <label class="form-label required">Kata Sandi</label>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 karakter" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label required">Konfirmasi Sandi</label>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-lock-check"></i>
                        </span>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi sandi" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-check">
                    <input type="checkbox" name="terms" class="form-check-input" required checked>
                    <span class="form-check-label small">Saya menyetujui Ketentuan Layanan &amp; Kebijakan Privasi MooWiFi.</span>
                </label>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="ti ti-user-plus me-1"></i> Buat Akun &amp; Mulai Coba Gratis
                </button>
            </div>
        </form>
    </div>
    <div class="card-footer text-center py-3 bg-body-tertiary">
        <span class="text-secondary small">Sudah memiliki akun MooWiFi?</span>
        <a href="{{ route('login') }}" class="ms-1 fw-bold text-decoration-none">Masuk di Sini</a>
    </div>
</div>
@endsection
