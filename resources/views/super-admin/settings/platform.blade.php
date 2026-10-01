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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Parameter Sistem & Preferensi Bisnis</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required">Nama Aplikasi</label>
                        <input type="text" class="form-control" value="MooWiFi - Otomatisasi Jaringan, Maksimalkan Cuan.">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Domain Utama Dashboard</label>
                        <input type="text" class="form-control" value="app.domain.com" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Durasi Uji Coba Default (Hari)</label>
                        <input type="number" class="form-control" value="14">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Mata Uang Platform</label>
                        <input type="text" class="form-control" value="IDR (Rupiah)" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Zona Waktu Default</label>
                        <input type="text" class="form-control" value="Asia/Jakarta (WIB)" readonly>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-primary">Simpan Pengaturan</button>
            </div>
        </div>
    </div>
</div>
@endsection
