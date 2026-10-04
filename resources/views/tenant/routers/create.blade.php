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
    copiedScript: false,
    copyScript(text) {
        navigator.clipboard.writeText(text);
        this.copiedScript = true;
        setTimeout(() => this.copiedScript = false, 3000);
    }
}">
    <div class="container-xl">
        <!-- Overview Banner -->
        <div class="alert alert-info bg-azure-lt border-azure mb-4" role="alert">
            <div class="d-flex align-items-start gap-3">
                <div class="text-azure mt-1">
                    <i class="ti ti-router fs-1"></i>
                </div>
                <div>
                    <h3 class="alert-title text-azure fw-bold mb-1">Panduan Integrasi Router MikroTik dengan MooWiFi</h3>
                    <p class="text-secondary small mb-0 lh-base">
                        MooWiFi terhubung langsung dengan RouterOS MikroTik untuk mengeksekusi <strong>Auto-Cut</strong> (isolir otomatis pelanggan nunggak) dan <strong>Auto-Restore</strong> (buka isolir detik itu juga saat tagihan lunas). Pilih metode koneksi yang sesuai dengan kondisi jaringan Anda di bawah ini.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('tenant.routers.store') }}" method="POST">
            @csrf
            <div class="row row-cards">
                <!-- Kolom Kiri: Form Konfigurasi -->
                <div class="col-lg-7">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-transparent border-bottom">
                            <h3 class="card-title text-dark">
                                <i class="ti ti-settings me-2 text-primary"></i> Formulir Parameter Koneksi
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required fw-bold">Nama Pengenal Router</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Router Core OLT / MikroTik CCR Wilayah 1" value="{{ old('name') }}" required>
                                <small class="form-hint">Beri nama unik untuk memudahkan identifikasi jika Anda memiliki lebih dari 1 router.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required fw-bold mb-2">Pilih Metode Penghubung ke MikroTik</label>
                                <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column gap-2">
                                    <!-- Opsi 1: Auto-VPN (Rekomendasi) -->
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="connection_type" value="VPN_TUNNEL" class="form-selectgroup-input" x-model="connectionType">
                                        <div class="form-selectgroup-label d-flex align-items-center p-3 text-start border rounded-3" :class="connectionType === 'VPN_TUNNEL' ? 'border-primary bg-primary-lt' : ''">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="fw-bold text-dark fs-3">Auto-VPN Tunneling (SSTP / WireGuard)</span>
                                                    <span class="badge bg-green text-white">Rekomendasi</span>
                                                </div>
                                                <div class="text-secondary small mt-1">
                                                    <strong>Wajib dipilih jika router menggunakan internet rumahan (IndiHome, Biznet, dll di balik CGNAT / Tanpa IP Publik)</strong>. Router melakukan dial-out keluar sehingga aman menembus modem ISP tanpa perlu sewa IP publik mahal.
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Opsi 2: Direct Public IP -->
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="connection_type" value="DIRECT" class="form-selectgroup-input" x-model="connectionType">
                                        <div class="form-selectgroup-label d-flex align-items-center p-3 text-start border rounded-3" :class="connectionType === 'DIRECT' ? 'border-primary bg-primary-lt' : ''">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark fs-3">Koneksi Langsung (Direct Public IP / DDNS)</span>
                                                <div class="text-secondary small mt-1">
                                                    Gunakan hanya jika router MikroTik Anda memiliki <strong>IP Publik Statis</strong> atau menggunakan domain DDNS MikroTik (<code class="text-dark">*.sn.mynetname.net</code>) dengan port forwarding API (8728) yang sudah dibuka.
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Form Isian: VPN_TUNNEL -->
                            <div x-show="connectionType === 'VPN_TUNNEL'" x-cloak class="border rounded-3 p-3 bg-light mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <h4 class="mb-0 text-dark fw-bold">
                                        <i class="ti ti-shield-lock text-success me-1"></i> Parameter Virtual Tunnel (Otomatis Dibuat)
                                    </h4>
                                    <span class="badge bg-azure-lt">Auto-Generated</span>
                                </div>

                                <input type="hidden" name="vpn_user" value="{{ $suggestedVpnUser }}">
                                <input type="hidden" name="vpn_password" value="{{ $suggestedVpnPass }}">
                                <input type="hidden" name="tunnel_ip" value="{{ $suggestedTunnelIp }}">

                                <div class="row g-2 mb-3">
                                    <div class="col-sm-7">
                                        <label class="form-label small fw-bold">Virtual Tunnel IP</label>
                                        <input type="text" class="form-control font-monospace bg-white" value="{{ $suggestedTunnelIp }}" readonly>
                                        <small class="form-hint">IP privat dedicated di dalam server VPN MooWiFi.</small>
                                    </div>
                                    <div class="col-sm-5">
                                        <label class="form-label small fw-bold">Port API Tunnel</label>
                                        <input type="number" name="port" class="form-control bg-white font-monospace" value="8728" required>
                                        <small class="form-hint">Standar port API RouterOS: 8728.</small>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-sm-6">
                                        <label class="form-label small fw-bold">Username API di MikroTik</label>
                                        <input type="text" name="username" class="form-control bg-white font-monospace" value="mwifi_api" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label small fw-bold">Password API di MikroTik</label>
                                        <input type="password" name="password" class="form-control bg-white font-monospace" value="{{ $suggestedVpnPass }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Isian: DIRECT -->
                            <div x-show="connectionType === 'DIRECT'" x-cloak class="border rounded-3 p-3 bg-light mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <h4 class="mb-0 text-dark fw-bold">
                                        <i class="ti ti-world text-primary me-1"></i> Parameter Akses IP Publik / DDNS
                                    </h4>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-sm-8">
                                        <label class="form-label required small fw-bold">Host / IP Publik / Domain DDNS</label>
                                        <input type="text" name="host" class="form-control font-monospace" placeholder="Contoh: 103.123.45.67 atau budinet.sn.mynetname.net" value="{{ old('host') }}">
                                        <small class="form-hint">IP Publik Statis router atau DNS Cloud MikroTik.</small>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label required small fw-bold">Port API RouterOS</label>
                                        <input type="number" name="port" class="form-control font-monospace" value="{{ old('port', 8728) }}" required>
                                        <small class="form-hint">Port default: 8728 (atau 8729 SSL).</small>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-sm-6">
                                        <label class="form-label required small fw-bold">Username API</label>
                                        <input type="text" name="username" class="form-control font-monospace" placeholder="Contoh: saas_api_user" value="{{ old('username') }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label required small fw-bold">Password API</label>
                                        <input type="password" name="password" class="form-control font-monospace" placeholder="Kata sandi user API">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="use_ssl" value="1" {{ old('use_ssl') ? 'checked' : '' }}>
                                        <span class="form-check-label small">Gunakan API-SSL (Port 8729 dengan sertifikat TLS)</span>
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <a href="{{ route('tenant.routers.index') }}" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ti ti-device-floppy me-1"></i> Simpan Router
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Panduan Interaktif, Script Generator & Troubleshooting -->
                <div class="col-lg-5">
                    <!-- Script Generator Box -->
                    <div class="card mb-3 shadow-sm border-primary">
                        <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between py-2">
                            <h3 class="card-title text-white fs-4 mb-0">
                                <i class="ti ti-terminal me-1"></i> Script MikroTik Siap Pakai
                            </h3>
                            <span class="badge bg-white text-primary fw-bold">1-Klik Salin</span>
                        </div>
                        <div class="card-body">
                            <!-- Panduan Script VPN -->
                            <div x-show="connectionType === 'VPN_TUNNEL'" x-cloak>
                                <p class="text-secondary small mb-2">
                                    Jalankan script di bawah ini pada <strong>Terminal Winbox</strong> MikroTik Anda. Script ini otomatis mengonfigurasi panggilan VPN SSTP dan akun API dengan hak terbatas:
                                </p>

                                @php
                                    $vpnScript = "/interface sstp-client add name=\"mwifi-tunnel\" connect-to=\"" . request()->getHost() . "\" user=\"" . $suggestedVpnUser . "\" password=\"" . $suggestedVpnPass . "\" profile=default-encryption disabled=no\n"
                                               . "/user group add name=saas-grp policy=read,write,api,test\n"
                                               . "/user add name=mwifi_api group=saas-grp password=\"" . $suggestedVpnPass . "\"\n"
                                               . "/ip service set api port=8728 disabled=no";
                                @endphp

                                <div class="position-relative mb-3">
                                    <pre class="bg-dark text-white p-3 rounded font-monospace small mb-2 user-select-all" style="font-size: 0.78rem; max-height: 180px; overflow-y: auto;">{{ $vpnScript }}</pre>
                                    <button type="button" 
                                            class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 shadow-sm"
                                            @click="copyScript(@js($vpnScript))">
                                        <i class="ti" :class="copiedScript ? 'ti-check text-success' : 'ti-copy'"></i>
                                        <span x-text="copiedScript ? 'Tersalin!' : 'Salin Script'"></span>
                                    </button>
                                </div>

                                <div class="steps steps-vertical mb-3 small">
                                    <div class="step-item active">
                                        <div class="h5 m-0 fw-bold">1. Salin Script di Atas</div>
                                        <div class="text-secondary">Klik tombol <strong>Salin Script</strong> untuk menyalin seluruh baris konfigurasi.</div>
                                    </div>
                                    <div class="step-item active">
                                        <div class="h5 m-0 fw-bold">2. Buka Winbox &amp; Terminal</div>
                                        <div class="text-secondary">Buka Winbox router Anda, klik menu <strong>New Terminal</strong> di sebelah kiri.</div>
                                    </div>
                                    <div class="step-item active">
                                        <div class="h5 m-0 fw-bold">3. Paste &amp; Tekan Enter</div>
                                        <div class="text-secondary">Klik kanan di area terminal lalu pilih <strong>Paste</strong>, kemudian tekan tombol Enter pada keyboard.</div>
                                    </div>
                                    <div class="step-item active">
                                        <div class="h5 m-0 fw-bold">4. Simpan &amp; Uji Koneksi</div>
                                        <div class="text-secondary">Klik tombol <strong>Simpan Router</strong> di bawah formulir ini, lalu lakukan pengujian koneksi.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Panduan Script DIRECT -->
                            <div x-show="connectionType === 'DIRECT'" x-cloak>
                                <p class="text-secondary small mb-2">
                                    Buka Winbox, klik <strong>New Terminal</strong>, lalu jalankan perintah berikut untuk mengaktifkan port API dan membuat akun akses terbatas:
                                </p>

                                @php
                                    $directScript = "/ip service set api port=8728 disabled=no\n"
                                                  . "/user group add name=saas-grp policy=read,write,api,test\n"
                                                  . "/user add name=saas_user group=saas-grp password=\"GANTI_DENGAN_PASSWORD_ANDA\"";
                                @endphp

                                <div class="position-relative mb-3">
                                    <pre class="bg-dark text-white p-3 rounded font-monospace small mb-2 user-select-all" style="font-size: 0.8rem;">{{ $directScript }}</pre>
                                    <button type="button" 
                                            class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 shadow-sm"
                                            @click="copyScript(@js($directScript))">
                                        <i class="ti" :class="copiedScript ? 'ti-check text-success' : 'ti-copy'"></i>
                                        <span x-text="copiedScript ? 'Tersalin!' : 'Salin Script'"></span>
                                    </button>
                                </div>

                                <div class="alert alert-warning py-2 small mb-0">
                                    <i class="ti ti-alert-triangle me-1"></i>
                                    Pastikan port API (8728) di modem atau router ISP Anda sudah di-<em>port forward</em> mengarah ke IP lokal MikroTik jika MikroTik berada di bawah modem bridge/router lain.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keamanan & Standar Akses -->
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-transparent border-bottom py-2">
                            <h4 class="card-title text-dark fs-4 mb-0">
                                <i class="ti ti-shield-check text-success me-1"></i> Standar Keamanan &amp; Hak Akses
                            </h4>
                        </div>
                        <div class="card-body small text-secondary">
                            <ul class="list-unstyled mb-0 lh-lg">
                                <li class="d-flex align-items-start gap-2 mb-2">
                                    <i class="ti ti-check text-success fs-3 mt-1"></i>
                                    <div><strong>Prinsip Hak Akses Minimum:</strong> User API hanya diberi izin <code>read, write, api, test</code>. Akses berbahaya seperti <code>reboot</code>, <code>sensitive</code>, <code>password</code>, dan <code>ftp</code> diblokir.</div>
                                </li>
                                <li class="d-flex align-items-start gap-2 mb-2">
                                    <i class="ti ti-check text-success fs-3 mt-1"></i>
                                    <div><strong>Enkripsi Terowongan:</strong> Semua instruksi API via Auto-VPN dienkripsi penuh menggunakan SSTP (TLS) sehingga data transaksi pelanggan tidak dapat disadap di jaringan publik.</div>
                                </li>
                                <li class="d-flex align-items-start gap-2">
                                    <i class="ti ti-check text-success fs-3 mt-1"></i>
                                    <div><strong>Privasi Data:</strong> MooWiFi tidak membaca histori penjelajahan internet warga, sistem hanya mengelola profil PPPoE / Simple Queue untuk penagihan.</div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Troubleshooting Accordion -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-transparent border-bottom py-2">
                            <h4 class="card-title text-dark fs-4 mb-0">
                                <i class="ti ti-help text-warning me-1"></i> Bantuan &amp; Solusi Jika Gagal Konek
                            </h4>
                        </div>
                        <div class="accordion accordion-flush" id="faqMikrotikHelp">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-2 small fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#trouble1">
                                        Status router tetap OFFLINE setelah script di-paste?
                                    </button>
                                </h2>
                                <div id="trouble1" class="accordion-collapse collapse" data-bs-parent="#faqMikrotikHelp">
                                    <div class="accordion-body small text-secondary pt-1 pb-3 lh-base">
                                        Buka Winbox menu <strong>Interfaces</strong>, cari interface <code>mwifi-tunnel</code>. Pastikan ada huruf <strong>R</strong> (Running). Jika tidak ada, pastikan router MikroTik Anda memiliki koneksi internet aktif untuk melakukan dial-out.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-2 small fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#trouble2">
                                        Apakah Firewall MikroTik memblokir port API?
                                    </button>
                                </h2>
                                <div id="trouble2" class="accordion-collapse collapse" data-bs-parent="#faqMikrotikHelp">
                                    <div class="accordion-body small text-secondary pt-1 pb-3 lh-base">
                                        Periksa menu <strong>IP -> Firewall -> Filter Rules</strong>. Jika ada rule dengan action <code>drop</code> untuk input chain, pastikan dibuatkan rule <code>accept</code> di baris paling atas untuk port <code>8728</code> dari interface <code>mwifi-tunnel</code>.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-2 small fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#trouble3">
                                        Router MikroTik tipe dan RouterOS apa saja yang didukung?
                                    </button>
                                </h2>
                                <div id="trouble3" class="accordion-collapse collapse" data-bs-parent="#faqMikrotikHelp">
                                    <div class="accordion-body small text-secondary pt-1 pb-3 lh-base">
                                        Mendukung seluruh perangkat RouterOS v6 dan v7 (hEX, hAP, RB450, RB750, RB1100, CCR1009/1016/1036/2004, hingga Cloud Hosted Router / CHR).
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
