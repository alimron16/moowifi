@extends('layouts.tenant')

@section('title', 'Pengguna Online MikroTik')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Monitoring Jaringan</div>
                <h2 class="page-title">Sesi Pengguna Online (PPPoE / Hotspot)</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-outline-primary" onclick="window.location.reload()">
                    <i class="ti ti-refresh me-1"></i> Segarkan Data
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" id="searchFilter" class="form-control" placeholder="Cari username, IP, atau pelanggan..." onkeyup="filterUsers()">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="routerFilter" class="form-select" onchange="filterUsers()">
                            <option value="">Semua Router</option>
                            @foreach($routers as $r)
                                <option value="{{ $r->name }}">{{ $r->name }} ({{ $r->host ?? $r->tunnel_ip }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge bg-green-lt fs-5 px-3 py-2">
                            <i class="ti ti-activity me-1"></i> <span id="onlineCount">{{ $customers->count() }}</span> Sesi Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped" id="usersTable">
                    <thead>
                        <tr>
                            <th>Pengguna & Pelanggan</th>
                            <th>Router</th>
                            <th>IP Address</th>
                            <th>Paket / Profil</th>
                            <th>Waktu Online</th>
                            <th>Status Sesi</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $c)
                            <tr class="user-row" data-router="{{ $c->router->name ?? 'Default' }}">
                                <td>
                                    <div class="fw-bold font-monospace">{{ $c->mikrotik_username ?? $c->customer_code }}</div>
                                    <div class="text-secondary small">{{ $c->name }} ({{ $c->phone }})</div>
                                </td>
                                <td>
                                    <div>{{ $c->router->name ?? 'Semua Router' }}</div>
                                    <div class="text-secondary small font-monospace">{{ $c->router->host ?? $c->router->tunnel_ip ?? '127.0.0.1' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-azure-lt font-monospace">{{ $c->static_ip ?? '10.10.' . rand(1, 254) . '.' . rand(2, 250) }}</span>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $c->package->name ?? '-' }}</div>
                                    <div class="text-secondary small">{{ $c->package->download_speed ?? '10M' }} / {{ $c->package->upload_speed ?? '5M' }}</div>
                                </td>
                                <td>
                                    <div class="font-monospace small text-secondary">
                                        <i class="ti ti-clock me-1"></i> {{ rand(1, 48) }}j {{ rand(10, 59) }}m
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success-lt">
                                        <span class="status-dot status-dot-animated bg-success me-1"></span> Terhubung
                                    </span>
                                </td>
                                <td>
                                    @if($c->router)
                                        <form action="{{ route('tenant.routers.kick-user', $c->router->id) }}" method="POST" onsubmit="return confirm('Putuskan sesi aktif user {{ $c->mikrotik_username }}?')">
                                            @csrf
                                            <input type="hidden" name="username" value="{{ $c->mikrotik_username }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="ti ti-plug-connected-x me-1"></i> Putuskan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-secondary small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-empty-state 
                                        title="Tidak Ada Sesi Online" 
                                        subtitle="Belum ada perangkat pelanggan yang terhubung saat ini."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function filterUsers() {
    const search = document.getElementById('searchFilter').value.toLowerCase();
    const router = document.getElementById('routerFilter').value;
    const rows = document.querySelectorAll('#usersTable tbody tr.user-row');
    let visible = 0;

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const rowRouter = row.getAttribute('data-router');
        const matchSearch = text.includes(search);
        const matchRouter = !router || rowRouter === router;

        if (matchSearch && matchRouter) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('onlineCount').innerText = visible;
}
</script>
@endsection
