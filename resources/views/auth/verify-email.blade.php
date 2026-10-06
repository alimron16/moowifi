@extends('layouts.guest')

@section('title', 'Verifikasi Alamat Email')

@section('guest-content')
<div class="card card-md shadow-sm">
    <div class="card-body text-center p-4">
        <div class="mb-3">
            <span class="avatar avatar-xl rounded-circle bg-primary-lt text-primary">
                <i class="ti ti-mail-forward fs-1"></i>
            </span>
        </div>
        <h2 class="h2 mb-2">Verifikasi Email Anda</h2>
        <p class="text-secondary mb-4">
            Terima kasih telah mendaftar di <strong>MooWiFi</strong>! Sebelum melanjutkan ke dasbor RT/RW Net, kami perlu memverifikasi kepemilikan alamat email Anda:
        </p>

        <div class="alert alert-info d-flex align-items-center justify-content-center gap-2 mb-4 py-2">
            <i class="ti ti-mail fs-2"></i>
            <span class="fw-bold">{{ Auth::user()->email }}</span>
        </div>

        <div class="alert alert-warning text-start mb-4 p-3 border-warning-subtle bg-warning-lt">
            <div class="d-flex align-items-start gap-2">
                <i class="ti ti-alert-triangle fs-2 text-warning flex-shrink-0 mt-1"></i>
                <div class="small">
                    <strong class="d-block mb-1 text-dark">Email Belum Masuk? Silakan Cek Folder Spam</strong>
                    <p class="mb-1 text-secondary">
                        Sistem kami telah mengirimkan tautan verifikasi. Jika belum muncul di kotak masuk utama, silakan periksa folder <strong>Spam / Junk</strong> atau tab <strong>Promosi (Promotions)</strong> di email Anda.
                    </p>
                    <p class="mb-0 text-secondary">
                        Jika email masuk ke folder Spam, tandai sebagai <strong>"Bukan Spam" (Not Spam)</strong> agar tautan verifikasi dapat dibuka dengan normal.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('verification.send') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-send me-1"></i> Kirim Ulang Tautan Verifikasi
            </button>
        </form>

        <div class="border-top pt-3 mt-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-ghost-danger btn-sm">
                    <i class="ti ti-logout me-1"></i> Keluar / Ganti Akun
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
