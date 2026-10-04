@extends('layouts.tenant')

@section('title', 'Tambah Router MikroTik')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Infrastruktur &amp; Otomasi Jaringan</div>
                <h2 class="page-title">Tambah Router MikroTik Baru</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.routers.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar Router
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body" x-data="{ 
    connectionType: 'VPN_TUNNEL',
    activeTab: 'script',
    copiedScript: false,
    copyScript(text) {
        navigator.clipboard.writeText(text);
        this.copiedScript = true;
        setTimeout(() => this.copiedScript = false, 2500);
    }
}">
    <div class="container-xl">
        @php
            $vpnScript = "/interface sstp-client add name=\"mwifi-tunnel\" connect-to=\"" . request()->getHost() . "\" user=\"" . $suggestedVpnUser . "\" password=\"" . $suggestedVpnPass . "\" profile=default-encryption disabled=no\n"
                       . "/user group add name=saas-grp policy=read,write,api,test\n"
                       . "/user add name=mwifi_api group=saas-grp password=\"" . $suggestedVpnPass . "\"\n"
                       . "/ip service set api port=8728 disabled=no";

            $directScript = "/ip service set api port=8728 disabled=no\n"
                          . "/user group add name=saas-grp policy=read,write,api,test\n"
                          . "/user add name=saas_user group=saas-grp password=\"GANTI_DENGAN_PASSWORD_ANDA\"";
        @endphp

        <form action="{{ route('tenant.routers.store') }}" method="POST">
            @csrf
            <div class="row row-cards align-items-start">
                <!-- Kolom Kiri: Form Konfigurasi -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h3 class="card-title text-dark">
                                <i class="ti ti-settings me-2 text-primary"></i> Parameter Router
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Nama Pengenal -->
                            <div class="mb-3">
                                <label class="form-label required">Nama Pengenal Router</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Router Core OLT / CCR Wilayah 1" value="{{ old('name') }}" required>
                                <div class="form-hint">Nama penanda untuk membedakan router jika memiliki beberapa titik jaringan.</div>
                            </div>

                            <!-- Pilihan Metode Koneksi -->
                            <div class="mb-3">
                                <label class="form-label required">Metode Koneksi</label>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-selectgroup-item w-100">
                                            <input type="radio" name="connection_type" value="VPN_TUNNEL" class="form-selectgroup-input" x-model="connectionType">
                                            <div class="form-selectgroup-label d-flex align-items-start text-start p-3 border rounded-3 w-100" :class="connectionType === 'VPN_TUNNEL' ? 'border-primary bg-primary-lt' : ''">
                                                <div class="me-3 mt-1">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div class="flex-fill">
                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <span class="fw-bold text-dark">Auto-VPN Tunneling (SSTP)</span>
                                                        <span class="badge bg-green text-white">Rekomendasi</span>
                                                    </div>
                                                    <div class="text-secondary small">
                                                        Untuk router tanpa IP Publik (modem IndiHome, Biznet, CGNAT). Router dial-out otomatis ke server MooWiFi.
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-selectgroup-item w-100">
                                            <input type="radio" name="connection_type" value="DIRECT" class="form-selectgroup-input" x-model="connectionType">
                                            <div class="form-selectgroup-label d-flex align-items-start text-start p-3 border rounded-3 w-100" :class="connectionType === 'DIRECT' ? 'border-primary bg-primary-lt' : ''">
                                                <div class="me-3 mt-1">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div class="flex-fill">
                                                    <div class="fw-bold text-dark mb-1">Koneksi Langsung (Direct IP / DDNS)</div>
                                                    <div class="text-secondary small">
                                                        Khusus jika router Anda memiliki IP Publik Statis atau DNS Cloud MikroTik dengan port API terbuka.
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Opsi: VPN TUNNEL -->
                            <div x-show="connectionType === 'VPN_TUNNEL'" x-cloak>
                                <input type="hidden" name="vpn_user" value="{{ $suggestedVpnUser }}">
                                <input type="hidden" name="vpn_password" value="{{ $suggestedVpnPass }}">
                                <input type="hidden" name="tunnel_ip" value="{{ $suggestedTunnelIp }}">

                                <div class="card bg-light-subtle border mb-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="fw-bold text-dark small">
                                                <i class="ti ti-link text-primary me-1"></i> Data Terowongan Virtual
                                            </div>
                                            <span class="badge bg-azure-lt">Auto-Generated</span>
                                        </div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-7">
                                                <label class="form-label small mb-1">Virtual Tunnel IP</label>
                                                <input type="text" class="form-control form-control-sm font-monospace bg-white" value="{{ $suggestedTunnelIp }}" readonly>
                                            </div>
                                            <div class="col-5">
                                                <label class="form-label small mb-1">Port API</label>
                                                <input type="number" name="port" class="form-control form-control-sm font-monospace bg-white" value="8728" required>
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label small mb-1">User API MikroTik</label>
                                                <input type="text" name="username" class="form-control form-control-sm font-monospace bg-white" value="mwifi_api" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small mb-1">Password API</label>
                                                <input type="password" name="password" class="form-control form-control-sm font-monospace bg-white" value="{{ $suggestedVpnPass }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Opsi: DIRECT IP -->
                            <div x-show="connectionType === 'DIRECT'" x-cloak>
                                <div class="card bg-light-subtle border mb-3">
                                    <div class="card-body p-3">
                                        <div class="fw-bold text-dark small mb-2">
                                            <i class="ti ti-world text-primary me-1"></i> Parameter IP Publik / Host
                                        </div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-8">
                                                <label class="form-label small mb-1 required">Host / IP Publik / DDNS</label>
                                                <input type="text" name="host" class="form-control form-control-sm font-monospace" placeholder="103.xxx.xxx.xxx atau domain.sn.mynetname.net" value="{{ old('host') }}">
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label small mb-1 required">Port API</label>
                                                <input type="number" name="port" class="form-control form-control-sm font-monospace" value="{{ old('port', 8728) }}" required>
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <label class="form-label small mb-1 required">Username API</label>
                                                <input type="text" name="username" class="form-control form-control-sm font-monospace" placeholder="saas_user" value="{{ old('username') }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small mb-1 required">Password API</label>
                                                <input type="password" name="password" class="form-control form-control-sm font-monospace" placeholder="Kata sandi">
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <label class="form-check form-check-inline m-0">
                                                <input class="form-check-input" type="checkbox" name="use_ssl" value="1" {{ old('use_ssl') ? 'checked' : '' }}>
                                                <span class="form-check-label small">Gunakan API-SSL (Port 8729 TLS)</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                <a href="{{ route('tenant.routers.index') }}" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ti ti-device-floppy me-1"></i> Simpan Router
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Script Generator & Panduan Praktis (Tabbed View) -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a href="#tab-script" class="nav-link" :class="activeTab === 'script' ? 'active' : ''" @click.prevent="activeTab = 'script'">
                                        <i class="ti ti-terminal me-1"></i> Script MikroTik
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tab-help" class="nav-link" :class="activeTab === 'help' ? 'active' : ''" @click.prevent="activeTab = 'help'">
                                        <i class="ti ti-help-circle me-1"></i> Bantuan &amp; Keamanan
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <!-- TAB 1: Script & Langkah Praktis -->
                            <div x-show="activeTab === 'script'" x-cloak>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="small text-secondary fw-semibold">
                                        <span x-show="connectionType === 'VPN_TUNNEL'">Konfigurasi Dial-Out SSTP &amp; User API:</span>
                                        <span x-show="connectionType === 'DIRECT'">Konfigurasi Port API &amp; User Akses:</span>
                                    </span>
                                    <button type="button" 
                                            class="btn btn-sm" 
                                            :class="copiedScript ? 'btn-success' : 'btn-outline-primary'"
                                            @click="copyScript(connectionType === 'VPN_TUNNEL' ? @js($vpnScript) : @js($directScript))">
                                        <i class="ti" :class="copiedScript ? 'ti-check' : 'ti-copy'"></i>
                                        <span x-text="copiedScript ? 'Tersalin ke Clipboard!' : 'Salin Script'"></span>
                                    </button>
                                </div>

                                <!-- Box Terminal Code -->
                                <div class="bg-dark text-white rounded-3 p-3 mb-3" style="border: 1px solid rgba(255,255,255,0.1);">
                                    <div class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom border-secondary border-opacity-25 small text-white-50">
                                        <span><i class="ti ti-terminal me-1"></i> Winbox Terminal Script</span>
                                        <span class="badge bg-secondary-lt" style="font-size: 0.7rem;">RouterOS Ready</span>
                                    </div>
                                    <pre class="m-0 text-white font-monospace" style="font-size: 0.78rem; line-height: 1.6; white-space: pre-wrap; word-break: break-all;" x-text="connectionType === 'VPN_TUNNEL' ? @js($vpnScript) : @js($directScript)"></pre>
                                </div>

                                <!-- Langkah-langkah Eksekusi (Clean Tabler List) -->
                                <div class="card bg-light-subtle border-0">
                                    <div class="card-body p-3">
                                        <div class="fw-bold text-dark small mb-2">
                                            <i class="ti ti-list-numbers text-primary me-1"></i> 4 Langkah Mudah Menghubungkan:
                                        </div>
                                        <div class="d-flex flex-column gap-2 small text-secondary">
                                            <div class="d-flex align-items-start gap-2">
                                                <span class="badge bg-primary text-white rounded-pill px-2 py-1 mt-0">1</span>
                                                <div>Klik tombol <strong>Salin Script</strong> di atas.</div>
                                            </div>
                                            <div class="d-flex align-items-start gap-2">
                                                <span class="badge bg-primary text-white rounded-pill px-2 py-1 mt-0">2</span>
                                                <div>Buka aplikasi <strong>Winbox</strong>, lalu klik menu <strong>New Terminal</strong>.</div>
                                            </div>
                                            <div class="d-flex align-items-start gap-2">
                                                <span class="badge bg-primary text-white rounded-pill px-2 py-1 mt-0">3</span>
                                                <div>Klik kanan di area terminal lalu pilih <strong>Paste</strong>, kemudian tekan <strong>Enter</strong>.</div>
                                            </div>
                                            <div class="d-flex align-items-start gap-2">
                                                <span class="badge bg-primary text-white rounded-pill px-2 py-1 mt-0">4</span>
                                                <div>Klik tombol <strong>Simpan Router</strong> di formulir sebelah kiri, lalu tekan <strong>Tes</strong> di daftar router.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 2: Bantuan, Keamanan & FAQ -->
                            <div x-show="activeTab === 'help'" x-cloak>
                                <!-- Keamanan -->
                                <div class="mb-3">
                                    <div class="fw-bold text-dark small mb-2 d-flex align-items-center gap-1">
                                        <i class="ti ti-shield-check text-success fs-3"></i> Standar Keamanan MooWiFi
                                    </div>
                                    <div class="card bg-light-subtle border-0 p-3 small text-secondary">
                                        <p class="mb-2 lh-base">
                                            <strong>Hak Akses Dibatasi:</strong> User API hanya diberikan izin <code>read, write, api, test</code>. Akses berbahaya seperti <code>reboot</code>, <code>sensitive</code>, <code>password</code>, dan <code>ftp</code> diblokir demi keamanan perangkat.
                                        </p>
                                        <p class="mb-0 lh-base">
                                            <strong>Enkripsi:</strong> Koneksi terowongan dilindungi enkripsi SSL/TLS, aman dari penyadapan jaringan publik.
                                        </p>
                                    </div>
                                </div>

                                <!-- FAQ / Troubleshooting -->
                                <div>
                                    <div class="fw-bold text-dark small mb-2 d-flex align-items-center gap-1">
                                        <i class="ti ti-help text-warning fs-3"></i> Tanya Jawab &amp; Pemecahan Masalah
                                    </div>
                                    <div class="accordion" id="accordionHelpRouter">
                                        <div class="accordion-item border rounded mb-2">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed py-2 px-3 small fw-semibold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#help1">
                                                    Status router tetap OFFLINE setelah script di-paste?
                                                </button>
                                            </h2>
                                            <div id="help1" class="accordion-collapse collapse" data-bs-parent="#accordionHelpRouter">
                                                <div class="accordion-body py-2 px-3 small text-secondary lh-base">
                                                    Buka Winbox menu <strong>Interfaces</strong>, cari <code>mwifi-tunnel</code>. Pastikan ada bendera <strong>R (Running)</strong>. Jika tidak ada, pastikan router MikroTik memiliki akses internet aktif untuk melakukan dial-out.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item border rounded mb-2">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed py-2 px-3 small fw-semibold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#help2">
                                                    Apakah ada port firewall yang harus dibuka?
                                                </button>
                                            </h2>
                                            <div id="help2" class="accordion-collapse collapse" data-bs-parent="#accordionHelpRouter">
                                                <div class="accordion-body py-2 px-3 small text-secondary lh-base">
                                                    Jika Anda memiliki filter firewall <code>drop input</code>, pastikan port <code>8728</code> diizinkan untuk interface <code>mwifi-tunnel</code> di menu <strong>IP -> Firewall -> Filter Rules</strong>.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item border rounded">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed py-2 px-3 small fw-semibold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#help3">
                                                    Tipe router MikroTik apa saja yang didukung?
                                                </button>
                                            </h2>
                                            <div id="help3" class="accordion-collapse collapse" data-bs-parent="#accordionHelpRouter">
                                                <div class="accordion-body py-2 px-3 small text-secondary lh-base">
                                                    Semua tipe RouterOS v6 dan v7 (hEX, hAP, RB450, RB750, RB1100, CCR, hingga Cloud Hosted Router / CHR).
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
