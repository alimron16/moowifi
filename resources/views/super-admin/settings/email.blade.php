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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Konfigurasi SMTP Utama Platform</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required">SMTP Host</label>
                        <input type="text" class="form-control" value="smtp.mailgun.org">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required">SMTP Port</label>
                        <input type="number" class="form-control" value="587">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label required">Enkripsi</label>
                        <input type="text" class="form-control" value="TLS" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Username SMTP</label>
                        <input type="text" class="form-control" value="postmaster@domain.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Password / Secret</label>
                        <input type="password" class="form-control" value="password12345678">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Nama Pengirim (From Name)</label>
                        <input type="text" class="form-control" value="MWIFI Billing Billing Engine">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Alamat Email Pengirim (From Address)</label>
                        <input type="email" class="form-control" value="noreply@domain.com">
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
