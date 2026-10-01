@extends('layouts.super-admin')

@section('title', 'Manajemen Tenant')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Organisasi Langganan</div>
                <h2 class="page-title">Kelola Tenant RT/RW Net</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-tenant">
                    <i class="ti ti-plus me-1"></i> Daftarkan Tenant Baru
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-header border-bottom-0 pb-0">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a href="{{ route('super-admin.tenants.index') }}" class="nav-link {{ !request('status') ? 'active fw-bold' : '' }}">
                            Semua Tenant (All)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('super-admin.tenants.index', ['status' => 'ACTIVE']) }}" class="nav-link {{ request('status') === 'ACTIVE' ? 'active fw-bold' : '' }}">
                            Aktif (Active)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('super-admin.tenants.index', ['status' => 'TRIAL']) }}" class="nav-link {{ request('status') === 'TRIAL' ? 'active fw-bold' : '' }}">
                            Uji Coba (Trial)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('super-admin.tenants.index', ['status' => 'SUSPENDED']) }}" class="nav-link {{ request('status') === 'SUSPENDED' ? 'active fw-bold' : '' }}">
                            Ditangguhkan (Suspended)
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('super-admin.tenants.index') }}" class="row g-2">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <div class="col-md-9">
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama tenant, kode, atau email..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i> Saring
                        </button>
                        <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-ghost-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Tenant & Kode</th>
                            <th>Owner Akun</th>
                            <th>Pelanggan</th>
                            <th>Router</th>
                            <th>Masa Uji Coba</th>
                            <th>Status</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tenants as $t)
                            <tr>
                                <td>
                                    <a href="{{ route('super-admin.tenants.show', $t->id) }}" class="fw-bold text-decoration-none">
                                        {{ $t->name }}
                                    </a>
                                    <div class="text-secondary small">Kode: {{ $t->code }} (ID: {{ $t->id }})</div>
                                </td>
                                <td>
                                    @php $owner = $t->users->first(); @endphp
                                    <div>{{ $owner->name ?? '-' }}</div>
                                    <div class="text-secondary small">{{ $owner->email ?? $t->email }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-blue-lt">{{ $t->customers_count }} User</span>
                                </td>
                                <td>
                                    <span class="badge bg-purple-lt">{{ $t->routers_count }} Router</span>
                                </td>
                                <td>
                                    {{ $t->trial_ends_at ? $t->trial_ends_at->format('d/m/Y') : 'Permanen' }}
                                </td>
                                <td>
                                    <x-badge :status="$t->status" :dot="true" />
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('super-admin.tenants.show', $t->id) }}" class="btn btn-sm btn-outline-secondary">
                                            Rincian
                                        </a>
                                        <form action="{{ route('super-admin.tenants.toggle-status', $t->id) }}" method="POST" onsubmit="return confirm('Ubah status tenant ini?')">
                                            @csrf
                                            @if($t->status === 'ACTIVE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Suspend</button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-outline-success">Aktifkan</button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-empty-state 
                                        title="Belum Ada Tenant" 
                                        subtitle="Daftarkan pemilik RT/RW Net pertama Anda untuk memulai bisnis SaaS."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tenants->hasPages())
                <div class="card-footer d-flex align-items-center">
                    {{ $tenants->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Add Tenant -->
<div class="modal modal-blur fade" id="modal-add-tenant" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('super-admin.tenants.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Daftarkan Tenant RT/RW Net Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-8">
                            <label class="form-label required">Nama Usaha / Brand</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: BudiNet Nusantara" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label required">Kode Singkat</label>
                            <input type="text" name="code" class="form-control text-uppercase" placeholder="BDN" maxlength="10" required>
                        </div>
                    </div>

                    <hr class="my-3">
                    <h4 class="card-title text-secondary">Akun Owner Tenant:</h4>

                    <div class="mb-3">
                        <label class="form-label required">Nama Lengkap Owner</label>
                        <input type="text" name="owner_name" class="form-control" placeholder="Budi Santoso" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label required">Email Login Owner</label>
                            <input type="email" name="owner_email" class="form-control" placeholder="budi@email.com" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label required">Nomor WhatsApp</label>
                            <input type="text" name="owner_phone" class="form-control" placeholder="08123456789" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Password Login Awal</label>
                        <input type="password" name="owner_password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Daftarkan Tenant</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
