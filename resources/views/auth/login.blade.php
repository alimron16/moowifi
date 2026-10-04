@extends('layouts.guest')

@section('title', 'Masuk ke Akun')

@section('guest-content')
<div class="card card-md">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">Masuk ke Dasbor</h2>
        <form action="{{ route('login') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label class="form-label">Alamat Email</label>
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <i class="ti ti-mail"></i>
                    </span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>
            <div class="mb-2">
                <label class="form-label">
                    Kata Sandi
                </label>
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <i class="ti ti-lock"></i>
                    </span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kata sandi akun" required>
                </div>
            </div>
            <div class="mb-2">
                <label class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input"/>
                    <span class="form-check-label">Ingat sesi saya di perangkat ini</span>
                </label>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ti ti-login me-2"></i>
                    Masuk
                </button>
            </div>
        </form>
    </div>
    <div class="card-footer text-center py-3 bg-body-tertiary">
        <span class="text-secondary small">Belum memiliki akun RT/RW Net?</span>
        <a href="{{ route('register') }}" class="ms-1 fw-bold text-decoration-none">Daftar Coba Gratis</a>
    </div>
</div>
@endsection
