@extends('layouts.tenant')

@section('title', 'Manajemen Pelanggan')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Operasional Jaringan</div>
                <h2 class="page-title">Daftar Pelanggan Internet</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.customers.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    Tambah Pelanggan Baru
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('tenant.customers.index') }}" class="row g-2">
                    <div class="col-md-5">
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama, kode pelanggan, no. HP, atau username PPP..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="ACTIVE" {{ request('status') === 'ACTIVE' ? 'selected' : '' }}>Aktif</option>
                            <option value="UNPAID" {{ request('status') === 'UNPAID' ? 'selected' : '' }}>Menunggak (Unpaid)</option>
                            <option value="ISOLATED" {{ request('status') === 'ISOLATED' ? 'selected' : '' }}>Terisolir (Auto-Cut)</option>
                            <option value="SUSPENDED" {{ request('status') === 'SUSPENDED' ? 'selected' : '' }}>Ditangguhkan (Suspended)</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="ti ti-filter me-1"></i> Saring
                        </button>
                        <a href="{{ route('tenant.customers.index') }}" class="btn btn-ghost-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Kode & Pelanggan</th>
                            <th>Kontak</th>
                            <th>Paket / Router</th>
                            <th>Akun MikroTik</th>
                            <th>Siklus Tagihan</th>
                            <th>Status</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $c)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $c->name }}</div>
                                    <div class="text-secondary small">{{ $c->customer_code }}</div>
                                </td>
                                <td>
                                    <div>{{ $c->phone }}</div>
                                    <div class="text-secondary small">{{ $c->address ?? '-' }}</div>
                                </td>
                                <td>
                                    <div>{{ $c->package->name ?? 'Tanpa Paket' }}</div>
                                    <div class="text-secondary small">Router: {{ $c->router->name ?? 'Default' }}</div>
                                </td>
                                <td>
                                    <div class="font-monospace small">{{ $c->mikrotik_username ?? '-' }}</div>
                                    <div class="text-secondary small">{{ $c->connection_type }}</div>
                                </td>
                                <td>
                                    <div>Tgl {{ $c->billing_day }} - JT: Tgl {{ $c->due_day }}</div>
                                    <div class="text-secondary small">{{ $c->billing_type }}</div>
                                </td>
                                <td>
                                    <x-badge :status="$c->status" :dot="true" />
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-ghost-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Opsi
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @if($c->status === 'ISOLATED')
                                                <form action="{{ route('tenant.customers.force-restore', $c->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="ti ti-check me-2"></i> Aktifkan Internet
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('tenant.customers.force-isolate', $c->id) }}" method="POST" onsubmit="return confirm('Isolir akses internet pelanggan ini?')">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="ti ti-wifi-off me-2"></i> Isolir Akses
                                                    </button>
                                                </form>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('tenant.customers.edit', $c->id) }}">
                                                <i class="ti ti-edit me-2"></i> Edit Data
                                            </a>
                                            <form action="{{ route('tenant.customers.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus data pelanggan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="ti ti-trash me-2"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-empty-state 
                                        title="Belum Ada Data Pelanggan" 
                                        subtitle="Tambahkan pelanggan pertama Anda untuk memulai penagihan otomatis."
                                    >
                                        <x-slot:action>
                                            <a href="{{ route('tenant.customers.create') }}" class="btn btn-primary">
                                                <i class="ti ti-plus me-1"></i> Tambah Pelanggan
                                            </a>
                                        </x-slot:action>
                                    </x-empty-state>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($customers->hasPages())
                <div class="card-footer d-flex align-items-center">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
