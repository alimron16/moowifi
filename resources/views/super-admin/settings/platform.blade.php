@extends('layouts.super-admin')

@section('title', 'Pengaturan Platform MooWiFi')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Konfigurasi Inti</div>
                <h2 class="page-title">Pengaturan Platform MooWiFi</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="{{ route('super-admin.settings.update') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Parameter Sistem & Preferensi Bisnis</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Nama Aplikasi Platform</label>
                            <input type="text" name="app_name" class="form-control" value="{{ $settings['app_name'] ?? 'MooWiFi - Otomatisasi Jaringan, Maksimalkan Cuan.' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Domain Utama Dashboard</label>
                            <input type="text" name="primary_domain" class="form-control" value="{{ $settings['primary_domain'] ?? 'app.domain.com' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Durasi Uji Coba Default (Hari)</label>
                            <input type="number" name="default_trial_days" class="form-control" value="{{ $settings['default_trial_days'] ?? 14 }}" min="1" max="90" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Email Kontak Dukungan</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? 'support@moowifi.id' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">No. WhatsApp Dukungan</label>
                            <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '081122334455' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Zona Waktu Platform</label>
                            <select name="timezone" class="form-select">
                                <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? '') === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB - UTC+7)</option>
                                <option value="Asia/Makassar" {{ ($settings['timezone'] ?? '') === 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA - UTC+8)</option>
                                <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? '') === 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT - UTC+9)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mata Uang Platform</label>
                            <input type="text" class="form-control bg-body-tertiary" value="IDR (Indonesian Rupiah)" readonly>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
