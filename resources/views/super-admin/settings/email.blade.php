@extends('layouts.super-admin')

@section('title', 'Platform Email System')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Email Gateway</div>
                <h2 class="page-title">Sistem Email Transaksional Platform SaaS</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="{{ route('super-admin.email.update') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Konfigurasi SMTP Utama Platform</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">SMTP Host</label>
                            <input type="text" name="mail_host" class="form-control" value="{{ $email['mail_host'] ?? 'smtp.gmail.com' }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">SMTP Port</label>
                            <input type="number" name="mail_port" class="form-control" value="{{ $email['mail_port'] ?? 587 }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Enkripsi</label>
                            <select name="mail_encryption" class="form-select">
                                <option value="tls" {{ ($email['mail_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ ($email['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Username SMTP</label>
                            <input type="text" name="mail_username" class="form-control" value="{{ $email['mail_username'] ?? '' }}" placeholder="akun@domain.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password / App Password</label>
                            <input type="password" name="mail_password" class="form-control" placeholder="••••••••••••••••">
                            <small class="form-hint">Kosongkan jika tidak ingin mengubah password saat ini.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Nama Pengirim (From Name)</label>
                            <input type="text" name="mail_from_name" class="form-control" value="{{ $email['mail_from_name'] ?? 'MooWiFi SaaS Platform' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Alamat Email Pengirim (From Address)</label>
                            <input type="email" name="mail_from_address" class="form-control" value="{{ $email['mail_from_address'] ?? 'noreply@moowifi.id' }}" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Konfigurasi SMTP
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
