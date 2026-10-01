@extends('layouts.tenant')

@section('title', 'Profil Bandwidth MikroTik')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Konfigurasi Jaringan</div>
                <h2 class="page-title">Profil Bandwidth & Paket MikroTik</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.packages.index') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Tambah Profil Paket
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Daftar Profil Kecepatan (PPP Profiles / Queue)</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Profil</th>
                            <th>Batas Kecepatan (Rate Limit)</th>
                            <th>Tarif Paket</th>
                            <th>Siklus Tagihan</th>
                            <th>Profil MikroTik Terdaftar</th>
                            <th>Status Profil</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $pkg)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $pkg->name }}</div>
                                    <div class="text-secondary small">{{ $pkg->description ?? 'Profil standar' }}</div>
                                </td>
                                <td>
                                    <div class="font-monospace text-primary fw-medium">
                                        <i class="ti ti-arrow-down text-success me-1"></i>{{ $pkg->download_speed }} / 
                                        <i class="ti ti-arrow-up text-primary me-1"></i>{{ $pkg->upload_speed }}
                                    </div>
                                    <div class="text-secondary small">Rate-Limit: {{ $pkg->download_speed }}/{{ $pkg->upload_speed }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $pkg->formatted_price }}</div>
                                </td>
                                <td>
                                    <div>{{ $pkg->billing_cycle_days }} Hari</div>
                                </td>
                                <td>
                                    <span class="badge bg-purple-lt font-monospace">
                                        {{ $pkg->mikrotik_profile ?? strtolower(str_replace(' ', '_', $pkg->name)) }}
                                    </span>
                                </td>
                                <td>
                                    <x-badge :status="$pkg->status" :dot="true" />
                                </td>
                                <td>
                                    <a href="{{ route('tenant.packages.index') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="ti ti-edit me-1"></i> Sesuaikan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-empty-state 
                                        title="Belum Ada Profil" 
                                        subtitle="Buat paket internet pertama Anda untuk menghasilkan profil MikroTik otomatis."
                                    >
                                        <x-slot:action>
                                            <a href="{{ route('tenant.packages.index') }}" class="btn btn-primary">
                                                <i class="ti ti-plus me-1"></i> Buat Paket
                                            </a>
                                        </x-slot:action>
                                    </x-empty-state>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Profil Isolir Otomatis (Auto-Cut Profile)</h3>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="fw-bold text-danger">Profil Isolir: ISOLATED</div>
                        <div class="text-secondary small">
                            Profil ini diterapkan otomatis pada pelanggan menunggak. Bandwidth dibatasi ke 64k/64k atau diarahkan ke halaman isolir tagihan MikroTik (Address List: ISOLIR).
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-danger-lt px-3 py-2 font-monospace">Rate-Limit: 64k/64k</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
