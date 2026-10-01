@extends('layouts.super-admin')

@section('title', 'Detail Tenant - ' . $tenant->name)

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Informasi & Audit Tenant</div>
                <h2 class="page-title">{{ $tenant->name }} ({{ $tenant->code }})</h2>
            </div>
            <div class="col-auto ms-auto d-print-none d-flex gap-2">
                <form action="{{ route('super-admin.tenants.toggle-status', $tenant->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn {{ $tenant->status === 'ACTIVE' ? 'btn-danger' : 'btn-success' }}">
                        @if($tenant->status === 'ACTIVE')
                            <i class="ti ti-ban me-1"></i> Tangguhkan Tenant (Suspend)
                        @else
                            <i class="ti ti-check me-1"></i> Aktifkan Tenant
                        @endif
                    </button>
                </form>
                <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Stat Cards -->
        <div class="row row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="Status Akun" 
                    value="{{ $tenant->status }}" 
                    icon="ti ti-shield" 
                    color="{{ $tenant->status === 'ACTIVE' ? 'green' : ($tenant->status === 'TRIAL' ? 'yellow' : 'danger') }}"
                    description="Masa trial: {{ $tenant->trial_ends_at ? $tenant->trial_ends_at->format('d/m/Y') : 'Permanen' }}"
                />
            </div>
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="Jumlah Pelanggan" 
                    value="{{ number_format($customersCount) }}" 
                    icon="ti ti-users" 
                    color="primary"
                    description="End-customers terdaftar"
                />
            </div>
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="Router Terpasang" 
                    value="{{ $routers->count() }}" 
                    icon="ti ti-router" 
                    color="purple"
                    description="{{ $routers->where('status', 'ONLINE')->count() }} Router Online"
                />
            </div>
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="Omzet Tagihan Tenant" 
                    value="Rp {{ number_format($revenue, 0, ',', '.') }}" 
                    icon="ti ti-wallet" 
                    color="teal"
                    description="Total dana dari {{ $invoicesCount }} tagihan"
                />
            </div>
        </div>

        <div class="row row-cards mb-4">
            <!-- Profil Tenant & Owner -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Profil Bisnis RT/RW Net & Kontak</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td class="text-secondary w-25">Nama Usaha:</td>
                                    <td class="fw-bold">{{ $tenant->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Kode Tenant:</td>
                                    <td><span class="badge bg-blue-lt font-monospace">{{ $tenant->code }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Email Bisnis:</td>
                                    <td>{{ $tenant->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">No. Telepon / WA:</td>
                                    <td>{{ $tenant->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Alamat Kantor:</td>
                                    <td>{{ $tenant->address ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Tanggal Bergabung:</td>
                                    <td>{{ $tenant->created_at->format('d F Y, H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Staf & Pengguna Tenant -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Pengguna & Staf Terdaftar</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email & HP</th>
                                    <th>Peran (Role)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tenant->users as $u)
                                    <tr>
                                        <td class="fw-bold">{{ $u->name }}</td>
                                        <td>
                                            <div>{{ $u->email }}</div>
                                            <div class="text-secondary small">{{ $u->phone ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-purple-lt">{{ $u->role }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-secondary py-3">Tidak ada data staf.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Router Milik Tenant -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Perangkat Router MikroTik Tenant</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Router</th>
                            <th>Konektivitas</th>
                            <th>Host / Tunnel IP</th>
                            <th>Port</th>
                            <th>Status Koneksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routers as $r)
                            <tr>
                                <td class="fw-bold">{{ $r->name }}</td>
                                <td>
                                    <span class="badge bg-azure-lt">{{ $r->connection_type }}</span>
                                </td>
                                <td class="font-monospace small">
                                    {{ $r->host ?? $r->tunnel_ip ?? '-' }}
                                </td>
                                <td>{{ $r->port }}</td>
                                <td>
                                    <x-badge :status="$r->status" :dot="true" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-3">Tenant belum mendaftarkan router MikroTik.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
