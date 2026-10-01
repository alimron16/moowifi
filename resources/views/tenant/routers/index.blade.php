@extends('layouts.tenant')

@section('title', 'Router MikroTik')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Infrastruktur Jaringan</div>
                <h2 class="page-title">Manajemen Router MikroTik</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.routers.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>
                    Tambah Router Baru
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Router</th>
                            <th>Metode Koneksi</th>
                            <th>Host / Tunnel IP</th>
                            <th>Port API</th>
                            <th>Pelanggan</th>
                            <th>Status Koneksi</th>
                            <th>Terakhir Terlihat</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routers as $r)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $r->name }}</div>
                                    <div class="text-secondary small">User API: {{ $r->username ?? '-' }}</div>
                                </td>
                                <td>
                                    <x-badge :status="$r->connection_type" />
                                </td>
                                <td>
                                    <code class="text-dark">
                                        {{ $r->connection_type === 'VPN_TUNNEL' ? ($r->tunnel_ip ?? 'Pending Tunnel') : ($r->host ?? '-') }}
                                    </code>
                                </td>
                                <td>{{ $r->port }}</td>
                                <td>
                                    <span class="badge bg-blue-lt">{{ $r->customers_count }} Pelanggan</span>
                                </td>
                                <td>
                                    <x-badge :status="$r->status" :dot="true" />
                                    @if($r->last_error)
                                        <div class="text-danger small mt-1" title="{{ $r->last_error }}">
                                            <i class="ti ti-alert-triangle"></i> Gagal koneksi
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-secondary small">
                                        {{ $r->last_seen_at ? $r->last_seen_at->diffForHumans() : 'Belum pernah' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('tenant.routers.test', $r->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Uji Koneksi Socket API">
                                                <i class="ti ti-plug me-1"></i> Tes
                                            </button>
                                        </form>
                                        <form action="{{ route('tenant.routers.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Hapus router ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-ghost-danger">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <x-empty-state 
                                        title="Belum Ada Router MikroTik" 
                                        subtitle="Hubungkan router MikroTik Anda menggunakan IP Publik atau Auto-VPN Tunnel."
                                    >
                                        <x-slot:action>
                                            <a href="{{ route('tenant.routers.create') }}" class="btn btn-primary">
                                                <i class="ti ti-plus me-1"></i> Tambah Router
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
    </div>
</div>
@endsection
