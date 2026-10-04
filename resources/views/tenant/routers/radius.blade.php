@extends('layouts.tenant')

@section('title', 'Server RADIUS Cloud')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Infrastruktur AAA & Sentralisasi Autentikasi</div>
                <h2 class="page-title">Server RADIUS Cloud</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.routers.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-router me-1"></i>
                    Kelola Router MikroTik
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Pengenalan & Edukasi Server RADIUS -->
        <div class="card mb-4 border-azure-subtle bg-azure-lt">
            <div class="card-body p-3 p-md-4">
                <div class="row align-items-center g-3">
                    <div class="col-auto d-none d-md-block">
                        <span class="avatar avatar-lg bg-azure text-white rounded">
                            <i class="ti ti-server-cog fs-1"></i>
                        </span>
                    </div>
                    <div class="col">
                        <h3 class="card-title text-dark mb-1">Sentralisasi Hotspot & PPPoE Tanpa Beban Memori Router</h3>
                        <p class="text-secondary mb-0">
                            <strong>Server RADIUS (Remote Authentication Dial-In User Service)</strong> adalah standar industri ISP untuk mengelola autentikasi (AAA) secara terpusat di server cloud. Dengan RADIUS, router MikroTik Anda tidak perlu menyimpan ribuan username & password di memori internalnya. MikroTik cukup menanyakan validasi login ke server cloud MooWifi.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cards mb-4">
            <!-- Parameter Koneksi RADIUS Cloud -->
            <div class="col-12 col-lg-5">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Parameter Koneksi Server RADIUS</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">HOST / IP SERVER RADIUS</label>
                            <div class="input-group">
                                <input type="text" class="form-control font-monospace" value="{{ $serverHost }}" id="radiusHost" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('radiusHost', 'Host berhasil disalin')">
                                    <i class="ti ti-copy"></i>
                                </button>
                            </div>
                            <small class="text-muted">Alamat host endpoint autentikasi cloud MooWifi.</small>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label text-secondary small fw-bold">AUTH PORT (UDP)</label>
                                <input type="text" class="form-control font-monospace" value="{{ $authPort }}" readonly>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-secondary small fw-bold">ACCT PORT (UDP)</label>
                                <input type="text" class="form-control font-monospace" value="{{ $acctPort }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">SECRET KEY TENANT (SHARED SECRET)</label>
                            <div class="input-group">
                                <input type="password" class="form-control font-monospace" value="{{ $radiusSecret }}" id="radiusSecretInput" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="toggleSecretVisibility()" title="Lihat / Sembunyikan">
                                    <i class="ti ti-eye" id="secretIcon"></i>
                                </button>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('radiusSecretInput', 'Secret Key berhasil disalin')">
                                    <i class="ti ti-copy"></i>
                                </button>
                            </div>
                            <small class="text-muted">Kunci enkripsi rahasia antara router MikroTik Anda dan Server RADIUS.</small>
                        </div>

                        <div class="alert alert-success d-flex align-items-center mb-0 py-2" role="alert">
                            <i class="ti ti-check-circle fs-2 me-2"></i>
                            <div>
                                <strong>Status: Siap Digunakan</strong>
                                <div class="small">Layanan RADIUS otomatis aktif pada paket {{ $tenant?->plan ?? 'SaaS' }}.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Script Konfigurasi 1-Baris MikroTik -->
            <div class="col-12 col-lg-7">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Script Konfigurasi Cepat RouterOS</h3>
                        <span class="badge bg-blue-lt">MikroTik RouterOS v6 & v7</span>
                    </div>
                    <div class="card-body">
                        <p class="text-secondary small mb-2">
                            Buka <strong>New Terminal</strong> di Winbox router MikroTik Anda, lalu salin dan tempel (paste) script berikut untuk menghubungkan MikroTik ke RADIUS Cloud:
                        </p>

                        <div class="position-relative mb-3">
                            <pre class="bg-dark text-white p-3 rounded font-monospace small mb-0" id="mikrotikRadiusScript" style="max-height: 220px; overflow-y: auto;"># 1. Tambahkan Server RADIUS Cloud MooWifi
/radius add service=ppp,hotspot address={{ $serverHost }} secret="{{ $radiusSecret }}" authentication-port={{ $authPort }} accounting-port={{ $acctPort }} timeout=3000ms comment="MOOWIFI-CLOUD-RADIUS"

# 2. Aktifkan RADIUS pada PPPoE Server & Interim Update 5 Menit
/ppp aaa set use-radius=yes accounting=yes interim-update=00:05:00

# 3. Aktifkan RADIUS pada Hotspot Profile (Default)
/ip hotspot profile set [ find default=yes ] use-radius=yes radius-accounting=yes</pre>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">
                                <i class="ti ti-info-circle me-1"></i> Mendukung autentikasi PPPoE dan Hotspot Voucher secara simultan.
                            </span>
                            <button type="button" class="btn btn-primary" onclick="copyScriptText()">
                                <i class="ti ti-copy me-1"></i> Salin Seluruh Script
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 Keunggulan Server RADIUS dibanding Metode Lama -->
        <div class="row row-cards mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Mengapa Harus Menggunakan Server RADIUS Cloud?</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="d-flex gap-3">
                                    <span class="avatar bg-blue-lt text-blue rounded">
                                        <i class="ti ti-cpu fs-2"></i>
                                    </span>
                                    <div>
                                        <div class="fw-bold text-dark mb-1">Router Anti Ngehang</div>
                                        <div class="text-secondary small">Router hemat resource CPU dan RAM karena tidak menyimpan ribuan database secret & hotspot voucher di flash disk MikroTik.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="d-flex gap-3">
                                    <span class="avatar bg-green-lt text-green rounded">
                                        <i class="ti ti-plug-connected fs-2"></i>
                                    </span>
                                    <div>
                                        <div class="fw-bold text-dark mb-1">Ganti Router Instan</div>
                                        <div class="text-secondary small">Jika router tersambar petir atau rusak, cukup pasang router baru dan hubungkan ke RADIUS tanpa perlu restore ribuan akun.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="d-flex gap-3">
                                    <span class="avatar bg-purple-lt text-purple rounded">
                                        <i class="ti ti-arrows-split fs-2"></i>
                                    </span>
                                    <div>
                                        <div class="fw-bold text-dark mb-1">Jelajah Multi-Tower</div>
                                        <div class="text-secondary small">Voucher hotspot yang dibeli pelanggan dapat dipakai login di beberapa router pemancar / tower yang berbeda (seamless roaming).</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="d-flex gap-3">
                                    <span class="avatar bg-red-lt text-red rounded">
                                        <i class="ti ti-shield-lock fs-2"></i>
                                    </span>
                                    <div>
                                        <div class="fw-bold text-dark mb-1">Isolir Otomatis Akurat</div>
                                        <div class="text-secondary small">Saat tagihan jatuh tempo, server RADIUS langsung menolak autentikasi atau mengarahkan pelanggan ke profil isolir tanpa intervensi manual.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Router yang Siap Menerima RADIUS -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Router MikroTik Terdaftar</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Nama Router</th>
                            <th>Koneksi</th>
                            <th>Host / IP</th>
                            <th>Status Sinkronisasi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routers as $router)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $router->name }}</div>
                                    <div class="text-secondary small">ID #{{ $router->id }}</div>
                                </td>
                                <td>
                                    <x-badge :status="$router->connection_type" />
                                </td>
                                <td>
                                    <code class="text-dark">{{ $router->connection_type === 'VPN_TUNNEL' ? ($router->tunnel_ip ?? 'VPN Tunnel') : ($router->host ?? '-') }}</code>
                                </td>
                                <td>
                                    <span class="badge {{ $router->status === 'ONLINE' ? 'bg-green-lt text-green' : 'bg-secondary-lt text-secondary' }}">
                                        {{ $router->status === 'ONLINE' ? 'Router Online' : 'Belum Terhubung' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('tenant.routers.index') }}" class="btn btn-sm btn-outline-secondary">
                                        Periksa Koneksi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-secondary">
                                    Belum ada router yang didaftarkan. Silakan <a href="{{ route('tenant.routers.create') }}">tambahkan router pertama Anda</a>.
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
function copyToClipboard(elementId, successMessage) {
    const copyText = document.getElementById(elementId);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    // Simple visual feedback
    alert(successMessage || 'Berhasil disalin ke clipboard');
}

function copyScriptText() {
    const scriptElement = document.getElementById('mikrotikRadiusScript');
    navigator.clipboard.writeText(scriptElement.innerText);
    alert('Script MikroTik RADIUS berhasil disalin. Tempelkan di New Terminal Winbox router Anda.');
}

function toggleSecretVisibility() {
    const input = document.getElementById('radiusSecretInput');
    const icon = document.getElementById('secretIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('ti-eye');
        icon.classList.add('ti-eye-off');
    } else {
        input.type = 'password';
        icon.classList.remove('ti-eye-off');
        icon.classList.add('ti-eye');
    }
}
</script>
@endsection
