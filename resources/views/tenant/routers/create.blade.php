@extends('layouts.tenant')

@section('title', 'Tambah Router MikroTik')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Koneksi Perangkat</div>
                <h2 class="page-title">Tambah Router MikroTik Baru</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.routers.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body" x-data="{ connectionType: 'DIRECT' }">
    <div class="container-xl">
        <form action="{{ route('tenant.routers.store') }}" method="POST">
            @csrf
            <div class="row row-cards">
                <div class="col-md-7">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Metode Konektivitas</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Nama Pengenal Router</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Router Core OLT / Router Wilayah 1" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Pilih Metode Penghubung</label>
                                <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="connection_type" value="DIRECT" class="form-selectgroup-input" x-model="connectionType" checked>
                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Metode 1: Koneksi Langsung (Direct Public IP / DDNS)</span>
                                                <div class="text-secondary small mt-1">Gunakan jika router Anda memiliki IP Publik Statis atau DDNS MikroTik (*.sn.mynetname.net).</div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="form-selectgroup-item flex-fill mt-2">
                                        <input type="radio" name="connection_type" value="VPN_TUNNEL" class="form-selectgroup-input" x-model="connectionType">
                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Metode 2: Auto-VPN Tunneling (SSTP / WireGuard)</span>
                                                <div class="text-secondary small mt-1">Gunakan jika router berada di balik modem internet rumahan (CGNAT / Tanpa IP Publik).</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Opsi DIRECT -->
                            <div x-show="connectionType === 'DIRECT'" x-cloak>
                                <div class="row g-2 mb-3">
                                    <div class="col-8">
                                        <label class="form-label required">Host / IP Publik / DDNS</label>
                                        <input type="text" name="host" class="form-control" placeholder="103.xxx.xxx.xxx atau budinet.sn.mynetname.net" value="{{ old('host') }}">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label required">Port API</label>
                                        <input type="number" name="port" class="form-control" value="{{ old('port', 8728) }}" required>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label required">Username API</label>
                                        <input type="text" name="username" class="form-control" placeholder="saas_user" value="{{ old('username') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label required">Password API</label>
                                        <input type="password" name="password" class="form-control" placeholder="Kata sandi user API">
                                    </div>
                                </div>
                            </div>

                            <!-- Opsi VPN_TUNNEL -->
                            <div x-show="connectionType === 'VPN_TUNNEL'" x-cloak>
                                <input type="hidden" name="vpn_user" value="{{ $suggestedVpnUser }}">
                                <input type="hidden" name="vpn_password" value="{{ $suggestedVpnPass }}">
                                <input type="hidden" name="tunnel_ip" value="{{ $suggestedTunnelIp }}">

                                <div class="row g-2 mb-3">
                                    <div class="col-8">
                                        <label class="form-label">Virtual Tunnel IP</label>
                                        <input type="text" class="form-control font-monospace" value="{{ $suggestedTunnelIp }}" readonly>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Port API Tunnel</label>
                                        <input type="number" name="port" class="form-control" value="8728" required>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label">User API di MikroTik</label>
                                        <input type="text" name="username" class="form-control" value="mwifi_api">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Password API di MikroTik</label>
                                        <input type="password" name="password" class="form-control" value="{{ $suggestedVpnPass }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panduan & Script Generator -->
                <div class="col-md-5">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Petunjuk Konfigurasi MikroTik</h3>
                        </div>
                        <div class="card-body">
                            <!-- Direct Guide -->
                            <div x-show="connectionType === 'DIRECT'" x-cloak>
                                <p class="text-secondary small">
                                    Jalankan baris perintah berikut di Terminal Winbox untuk mengaktifkan API RouterOS:
                                </p>
                                <pre class="bg-dark text-white p-3 rounded font-monospace small">/ip service set api port=8728 disabled=no
/user group add name=saas-grp policy=read,write,api,test
/user add name=saas_user group=saas-grp password="password_anda"</pre>
                            </div>

                            <!-- VPN Script -->
                            <div x-show="connectionType === 'VPN_TUNNEL'" x-cloak>
                                <p class="text-secondary small">
                                    Salin (copy) 1 blok script di bawah ini dan tempelkan (paste) langsung ke <strong>Terminal Winbox</strong> MikroTik Anda:
                                </p>
                                <pre class="bg-dark text-white p-3 rounded font-monospace small user-select-all">/interface sstp-client add name="mwifi-tunnel" connect-to="{{ request()->getHost() }}" user="{{ $suggestedVpnUser }}" password="{{ $suggestedVpnPass }}" profile=default-encryption disabled=no
/user group add name=saas-grp policy=read,write,api,test
/user add name=mwifi_api group=saas-grp password="{{ $suggestedVpnPass }}"
/ip service set api port=8728 disabled=no</pre>
                                <div class="text-secondary small mt-2">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Setelah script dijalankan di Winbox, klik tombol Simpan di bawah dan lakukan uji koneksi.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2 bg-transparent border-0 px-0">
                <a href="{{ route('tenant.routers.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i> Simpan Router
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
