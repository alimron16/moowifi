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
                        <input type="text" class="form-control" value="Fonnte Official / Wablas Gateway" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">API Secret Token</label>
                        <input type="password" class="form-control" value="platform_secret_token_12345">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Nomor WhatsApp Pengirim</label>
                        <input type="text" class="form-control" value="081122334455">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Status Koneksi</label>
                        <div>
                            <span class="badge bg-success-lt px-3 py-2">
                                <span class="status-dot status-dot-animated bg-success me-1"></span> TERHUBUNG
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-primary">Simpan Konfigurasi</button>
            </div>
        </div>
    </div>
</div>
@endsection
