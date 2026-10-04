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
                            <th>Nama Router & IP</th>
                            <th class="d-none d-lg-table-cell">Metode Koneksi</th>
                            <th class="d-none d-md-table-cell">Host / Tunnel IP</th>
                            <th class="d-none d-xl-table-cell">Port API</th>
                            <th class="d-none d-md-table-cell">Pelanggan</th>
                            <th>Status</th>
                            <th class="d-none d-md-table-cell">Terakhir Terlihat</th>
                            <th class="w-1 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routers as $r)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $r->name }}</div>
                                    <!-- Mobile-only IP and connection info -->
                                    <div class="d-md-none mt-1 d-flex flex-wrap gap-1 align-items-center">
                                        <code class="text-secondary small" style="font-size: 0.72rem;">
                                            {{ $r->connection_type === 'VPN_TUNNEL' ? ($r->tunnel_ip ?? 'VPN Tunnel') : ($r->host ?? '-') }}
                                        </code>
                                        <span class="badge bg-blue-lt" style="font-size: 0.7rem;">{{ $r->customers_count }} User</span>
                                    </div>
                                    <div class="text-secondary small d-none d-md-block">User API: {{ $r->username ?? '-' }}</div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <x-badge :status="$r->connection_type" />
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <code class="text-dark">
                                        {{ $r->connection_type === 'VPN_TUNNEL' ? ($r->tunnel_ip ?? 'Pending Tunnel') : ($r->host ?? '-') }}
                                    </code>
                                </td>
                                <td class="d-none d-xl-table-cell">{{ $r->port }}</td>
                                <td class="d-none d-md-table-cell">
                                    <span class="badge bg-blue-lt">{{ $r->customers_count }} Pelanggan</span>
                                </td>
                                <td>
                                    <x-badge :status="$r->status" :dot="true" />
                                    @if($r->last_error)
                                        <div class="text-danger small mt-1" title="{{ $r->last_error }}" style="font-size: 0.7rem;">
                                            <i class="ti ti-alert-triangle"></i> Gagal koneksi
                                        </div>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="text-secondary small">
                                        {{ $r->last_seen_at ? $r->last_seen_at->diffForHumans() : 'Belum pernah' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if($r->connection_type === 'VPN_TUNNEL')
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-script-{{ $r->id }}" title="Lihat Script Winbox">
                                                <i class="ti ti-terminal me-1"></i> Script
                                            </button>
                                        @endif
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

                                    @if($r->connection_type === 'VPN_TUNNEL')
                                        <!-- Modal Script Winbox -->
                                        @php
                                            $modalScript = "/interface sstp-client add name=\"mwifi-tunnel\" connect-to=\"" . request()->getHost() . "\" user=\"" . $r->vpn_user . "\" password=\"" . $r->decrypted_vpn_password . "\" profile=default-encryption disabled=no\n"
                                                         . "/user group add name=saas-grp policy=read,write,api,test\n"
                                                         . "/user add name=mwifi_api group=saas-grp password=\"" . $r->decrypted_vpn_password . "\"\n"
                                                         . "/ip service set api port=8728 disabled=no";
                                        @endphp
                                        <div class="modal modal-blur fade" id="modal-script-{{ $r->id }}" tabindex="-1" role="dialog" aria-hidden="true" x-data="{ copied: false }">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="ti ti-terminal me-1 text-primary"></i> Script MikroTik: {{ $r->name }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <p class="text-secondary small mb-2">
                                                            Salin baris script berikut ke <strong>New Terminal</strong> di Winbox router Anda:
                                                        </p>
                                                        <div class="position-relative mb-3">
                                                            <pre class="bg-dark text-white p-3 rounded font-monospace small user-select-all mb-0" style="font-size: 0.78rem; max-height: 200px; overflow-y: auto;">{{ $modalScript }}</pre>
                                                        </div>
                                                        <div class="alert alert-info py-2 small mb-0">
                                                            <i class="ti ti-info-circle me-1"></i>
                                                            Virtual Tunnel IP: <code>{{ $r->tunnel_ip }}</code> | User API: <code>{{ $r->username ?? 'mwifi_api' }}</code>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="button" class="btn btn-primary" @click="navigator.clipboard.writeText(@js($modalScript)); copied = true; setTimeout(() => copied = false, 2500)">
                                                            <i class="ti" :class="copied ? 'ti-check text-success' : 'ti-copy'"></i>
                                                            <span x-text="copied ? 'Tersalin ke Clipboard!' : 'Salin Script'"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
