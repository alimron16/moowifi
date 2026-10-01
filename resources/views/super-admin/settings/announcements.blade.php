@extends('layouts.super-admin')

@section('title', 'Pengumuman Platform (Announcements)')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Broadcast Komunikasi</div>
                <h2 class="page-title">Pengumuman & Pemberitahuan Platform</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Buat Pengumuman Baru
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pengumuman Aktif ke Seluruh Tenant</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Judul Pengumuman</th>
                            <th>Kategori</th>
                            <th>Tanggal Tayang</th>
                            <th>Target Penerima</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="fw-bold">Pembaruan Sistem Billing Engine v2.0</div>
                                <div class="text-secondary small">Integrasi multi-gateway Duitku, Midtrans, Xendit, Tripay & WhatsApp QR Scan.</div>
                            </td>
                            <td><span class="badge bg-blue-lt">Fitur Baru</span></td>
                            <td class="font-monospace small text-secondary">02/10/2026</td>
                            <td>Semua Tenant RT/RW Net</td>
                            <td><span class="badge bg-success-lt">TAYANG</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
