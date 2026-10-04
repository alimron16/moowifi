@extends('layouts.super-admin')

@section('title', 'Platform WhatsApp System')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Pusat Pesan Platform</div>
                <h2 class="page-title">Sistem WhatsApp Resmi Platform SaaS</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="{{ route('super-admin.whatsapp.update') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Konfigurasi Pengirim WhatsApp Platform</h3>
                </div>
                <div class="card-body">
                    <p class="text-secondary">
                        Nomor WhatsApp ini digunakan untuk mengirimkan kode OTP login, notifikasi tagihan langganan SaaS, dan pengumuman pemeliharaan sistem kepada pemilik RT/RW Net.
                    </p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Provider API Platform</label>
                            <input type="text" name="provider" class="form-control" value="{{ $wa['provider'] ?? 'Fonnte Official / Wablas Gateway' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">API Secret Token</label>
                            <input type="password" name="token" class="form-control" value="{{ $wa['token'] ?? '' }}" placeholder="Token API WhatsApp resmi platform" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Nomor WhatsApp Pengirim</label>
                            <input type="text" name="sender_number" class="form-control" value="{{ $wa['sender_number'] ?? '081122334455' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Gateway</label>
                            <label class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($wa['is_active'] ?? true) ? 'checked' : '' }}>
                                <span class="form-check-label">Aktifkan Pengiriman Notifikasi WhatsApp Platform</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Konfigurasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
