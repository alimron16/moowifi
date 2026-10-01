@extends('layouts.tenant')

@section('title', 'Pusat Pengaturan')

@section('tenant-content')
<div class="page-header d-print-none mb-3">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Konfigurasi Sistem</div>
                <h2 class="page-title">Pusat Pengaturan Tenant</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <!-- Top Horizontal Navigation Tabs (Tanpa Sidebar Dobel di Dalam Halaman) -->
            <div class="card-header border-bottom p-0">
                <ul class="nav nav-tabs card-header-tabs m-0 overflow-auto flex-nowrap" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item">
                        <a href="#tab-business" class="nav-link py-3 px-3 d-flex align-items-center gap-1 {{ $activeTab === 'business' ? 'active' : '' }}" data-bs-toggle="tab">
                            <i class="ti ti-building fs-2 text-primary"></i>
                            <span>Profil Usaha</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-billing" class="nav-link py-3 px-3 d-flex align-items-center gap-1 {{ $activeTab === 'billing' ? 'active' : '' }}" data-bs-toggle="tab">
                            <i class="ti ti-receipt fs-2 text-primary"></i>
                            <span>Billing & Auto-Cut</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-email" class="nav-link py-3 px-3 d-flex align-items-center gap-1 {{ $activeTab === 'email' ? 'active' : '' }}" data-bs-toggle="tab">
                            <i class="ti ti-mail fs-2 text-danger"></i>
                            <span>Email (Gmail)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-payment" class="nav-link py-3 px-3 d-flex align-items-center gap-1 {{ $activeTab === 'payment' ? 'active' : '' }}" data-bs-toggle="tab">
                            <i class="ti ti-credit-card fs-2 text-success"></i>
                            <span>Payment Gateway</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-whatsapp" class="nav-link py-3 px-3 d-flex align-items-center gap-1 {{ $activeTab === 'whatsapp' ? 'active' : '' }}" data-bs-toggle="tab">
                            <i class="ti ti-brand-whatsapp fs-2 text-success"></i>
                            <span>WhatsApp Gateway</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-templates" class="nav-link py-3 px-3 d-flex align-items-center gap-1 {{ $activeTab === 'templates' ? 'active' : '' }}" data-bs-toggle="tab">
                            <i class="ti ti-template fs-2 text-cyan"></i>
                            <span>Template Pesan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-users" class="nav-link py-3 px-3 d-flex align-items-center gap-1 {{ $activeTab === 'users' ? 'active' : '' }}" data-bs-toggle="tab">
                            <i class="ti ti-users fs-2 text-azure"></i>
                            <span>Staf & Hak Akses</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tab Contents (Full Width) -->
            <div class="card-body p-4">
                <div class="tab-content">
                    <!-- 1. Profil Usaha -->
                    <div class="tab-pane {{ $activeTab === 'business' ? 'active show' : '' }}" id="tab-business">
                        <h3 class="card-title mb-1">Profil Bisnis RT/RW Net</h3>
                        <p class="text-secondary small mb-3">Informasi legalitas dan identitas usaha Anda yang dicantumkan pada tagihan dan invoice pelanggan.</p>
                        <form action="{{ route('tenant.settings.business') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label required">Nama Brand / Usaha</label>
                                <input type="text" name="name" class="form-control" value="{{ $tenant->name }}" required>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Nomor Kontak WhatsApp Layanan</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $tenant->phone }}" required>
                                    <small class="form-hint">Dicantumkan pada invoice dan payment link pelanggan.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Usaha Resmi</label>
                                    <input type="email" name="email" class="form-control" value="{{ $tenant->email }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat Kantor / Domisili</label>
                                <textarea name="address" class="form-control" rows="3">{{ $tenant->address }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Profil Bisnis
                            </button>
                        </form>
                    </div>

                    <!-- 2. Billing & Auto-Cut -->
                    <div class="tab-pane {{ $activeTab === 'billing' ? 'active show' : '' }}" id="tab-billing">
                        <h3 class="card-title mb-1">Kebijakan Billing & Jadwal Auto-Cut Isolir</h3>
                        <p class="text-secondary small mb-3">Atur tanggal penerbitan invoice otomatis, batas jatuh tempo, dan toleransi keterlambatan pelanggan.</p>
                        @php
                            $billing = $tenant->getSetting('billing', []);
                        @endphp
                        <form action="{{ route('tenant.settings.billing-policy') }}" method="POST">
                            @csrf
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Default Tanggal Penagihan Bulanan</label>
                                    <input type="number" name="default_billing_day" class="form-control" value="{{ $billing['default_billing_day'] ?? 1 }}" min="1" max="28" required>
                                    <small class="form-hint">Hari ke- setiap bulan tanggal invoice terbit otomatis.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Default Tanggal Jatuh Tempo</label>
                                    <input type="number" name="default_due_day" class="form-control" value="{{ $billing['default_due_day'] ?? 10 }}" min="1" max="28" required>
                                    <small class="form-hint">Batas akhir pembayaran tagihan sebelum status berubah overdue.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Masa Tenggang (Grace Period)</label>
                                    <div class="input-group">
                                        <input type="number" name="default_grace_period_days" class="form-control" value="{{ $billing['default_grace_period_days'] ?? 3 }}" min="0" max="30" required>
                                        <span class="input-group-text">Hari</span>
                                    </div>
                                    <small class="form-hint">Toleransi keterlambatan setelah jatuh tempo sebelum isolir dieksekusi.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Jam Eksekusi Auto-Cut Isolir</label>
                                    <input type="time" name="auto_cut_hour" class="form-control" value="{{ $billing['auto_cut_hour'] ?? '00:15' }}" required>
                                    <small class="form-hint">Waktu eksekusi isolir oleh sistem cron scheduler malam hari.</small>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label required">Nama Profile Isolir di MikroTik</label>
                                    <input type="text" name="isolation_profile" class="form-control" value="{{ $billing['isolation_profile'] ?? 'ISOLIR' }}" required>
                                    <small class="form-hint">Nama profil PPPoE/Hotspot di RouterOS MikroTik yang diarahkan ke halaman isolir.</small>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" name="auto_cut_enabled" value="1" {{ ($billing['auto_cut_enabled'] ?? true) ? 'checked' : '' }}>
                                        <span class="form-check-label fw-bold">Aktifkan Auto-Cut Isolir Otomatis secara Global</span>
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Kebijakan Billing
                            </button>
                        </form>
                    </div>

                    <!-- 3. Akun Email (Gmail SMTP) -->
                    <div class="tab-pane {{ $activeTab === 'email' ? 'active show' : '' }}" id="tab-email">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="ti ti-brand-google fs-1 text-danger"></i>
                            <div>
                                <h3 class="card-title mb-0">Pengaturan Email Tagihan (Gmail SMTP)</h3>
                                <div class="text-secondary small">Kirim invoice berlampiran PDF langsung menggunakan alamat Gmail Anda sendiri</div>
                            </div>
                        </div>

                        <div class="alert alert-info py-2 small mb-4">
                            <div class="fw-bold mb-1"><i class="ti ti-info-circle me-1"></i> Cara Menggunakan Akun Gmail Anda:</div>
                            <ol class="mb-0 ps-3">
                                <li>Buka Akun Google Anda di <code>myaccount.google.com/security</code></li>
                                <li>Pastikan <strong>Verifikasi 2 Langkah (2-Step Verification)</strong> telah aktif</li>
                                <li>Cari menu <strong>Sandi Aplikasi (App Passwords)</strong></li>
                                <li>Buat sandi aplikasi baru dengan nama "MooWiFi Billing", lalu salin 16 karakter kata sandi tersebut ke formulir di bawah.</li>
                            </ol>
                        </div>

                        @php
                            $mail = $tenant->getSetting('mail', []);
                        @endphp
                        <form action="{{ route('tenant.settings.email') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label required">Alamat Email Gmail Anda</label>
                                <input type="email" name="gmail_username" class="form-control" value="{{ $mail['username'] ?? '' }}" placeholder="namaanda@gmail.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label {{ empty($mail['password']) ? 'required' : '' }}">Google App Password (16 Karakter)</label>
                                <input type="password" name="gmail_password" class="form-control" placeholder="{{ !empty($mail['password']) ? '•••••••••••••••• (Tersimpan)' : 'Contoh: abcd efgh ijkl mnop' }}">
                                <small class="form-hint">Gunakan Sandi Aplikasi (App Password), bukan password login email biasa Anda.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Nama Pengirim (Sender Name)</label>
                                <input type="text" name="from_name" class="form-control" value="{{ $mail['from_name'] ?? $tenant->name }}" placeholder="Contoh: BudiNet Billing" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Konfigurasi Gmail
                            </button>
                        </form>

                        <hr>

                        <h4 class="card-title text-secondary">Uji Coba Pengiriman Email:</h4>
                        <form action="{{ route('tenant.settings.test-email') }}" method="POST" class="row g-2">
                            @csrf
                            <div class="col-md-8">
                                <input type="email" name="test_recipient" class="form-control" placeholder="Kirim email tes ke alamat ini..." required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="ti ti-send me-1"></i> Kirim Email Tes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 4. Payment Gateway (Multi-Provider) -->
                    <div class="tab-pane {{ $activeTab === 'payment' ? 'active show' : '' }}" id="tab-payment">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h3 class="card-title mb-0">Integrasi Multi-Payment Gateway</h3>
                                <div class="text-secondary small">Dukung Duitku, Midtrans, Xendit, dan Tripay langsung ke rekening bisnis Anda</div>
                            </div>
                            <span class="badge bg-primary-lt">Direct-to-Merchant</span>
                        </div>

                        <ul class="nav nav-pills mb-3" role="tablist">
                            <li class="nav-item">
                                <a href="#gw-duitku" class="nav-link active py-2 px-3 d-flex align-items-center gap-1" data-bs-toggle="pill">
                                    <span class="status-dot {{ isset($gateways['DUITKU']) && $gateways['DUITKU']->is_active ? 'bg-success' : 'bg-secondary' }}"></span>
                                    Duitku
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#gw-midtrans" class="nav-link py-2 px-3 d-flex align-items-center gap-1" data-bs-toggle="pill">
                                    <span class="status-dot {{ isset($gateways['MIDTRANS']) && $gateways['MIDTRANS']->is_active ? 'bg-success' : 'bg-secondary' }}"></span>
                                    Midtrans (Snap)
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#gw-xendit" class="nav-link py-2 px-3 d-flex align-items-center gap-1" data-bs-toggle="pill">
                                    <span class="status-dot {{ isset($gateways['XENDIT']) && $gateways['XENDIT']->is_active ? 'bg-success' : 'bg-secondary' }}"></span>
                                    Xendit
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#gw-tripay" class="nav-link py-2 px-3 d-flex align-items-center gap-1" data-bs-toggle="pill">
                                    <span class="status-dot {{ isset($gateways['TRIPAY']) && $gateways['TRIPAY']->is_active ? 'bg-success' : 'bg-secondary' }}"></span>
                                    Tripay
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content border rounded p-3 bg-white">
                            <!-- Duitku Form -->
                            <div class="tab-pane active show" id="gw-duitku">
                                @php
                                    $duitku = $gateways['DUITKU'] ?? null;
                                    $dCreds = $duitku->credentials ?? [];
                                @endphp
                                <form action="{{ route('tenant.payment-methods.update-gateway') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="provider" value="DUITKU">
                                    <div class="mb-3">
                                        <label class="form-label required">Merchant Code Duitku</label>
                                        <input type="text" name="merchant_code" class="form-control" value="{{ $dCreds['merchant_code'] ?? '' }}" placeholder="D12345" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">API Key (Merchant Key)</label>
                                        <input type="password" name="api_key" class="form-control" value="{{ $dCreds['api_key'] ?? '' }}" placeholder="••••••••••••••••" required>
                                        <small class="form-hint">Kredensial disimpan terenkripsi (AES-256).</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Environment</label>
                                        <select name="environment" class="form-select">
                                            <option value="sandbox" {{ ($dCreds['environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox (Mode Pengujian)</option>
                                            <option value="production" {{ ($dCreds['environment'] ?? '') === 'production' ? 'selected' : '' }}>Production (Mode Nyata)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($duitku->is_active ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Duitku di Halaman Pembayaran</span>
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Webhook Callback URL</label>
                                        <input type="text" class="form-control font-monospace small bg-light" value="{{ url('/api/webhooks/duitku') }}" readonly>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Duitku</button>
                                </form>
                            </div>

                            <!-- Midtrans Form -->
                            <div class="tab-pane" id="gw-midtrans">
                                @php
                                    $midtrans = $gateways['MIDTRANS'] ?? null;
                                    $mCreds = $midtrans->credentials ?? [];
                                @endphp
                                <form action="{{ route('tenant.payment-methods.update-gateway') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="provider" value="MIDTRANS">
                                    <div class="mb-3">
                                        <label class="form-label required">Server Key</label>
                                        <input type="password" name="server_key" class="form-control" value="{{ $mCreds['server_key'] ?? '' }}" placeholder="SB-Mid-server-••••••••" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Client Key</label>
                                        <input type="text" name="client_key" class="form-control" value="{{ $mCreds['client_key'] ?? '' }}" placeholder="SB-Mid-client-••••••••">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Environment</label>
                                        <select name="environment" class="form-select">
                                            <option value="sandbox" {{ ($mCreds['environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox (Mode Pengujian)</option>
                                            <option value="production" {{ ($mCreds['environment'] ?? '') === 'production' ? 'selected' : '' }}>Production (Mode Nyata)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($midtrans->is_active ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Midtrans Snap di Halaman Pembayaran</span>
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Notification Webhook URL</label>
                                        <input type="text" class="form-control font-monospace small bg-light" value="{{ url('/api/webhooks/midtrans') }}" readonly>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Midtrans</button>
                                </form>
                            </div>

                            <!-- Xendit Form -->
                            <div class="tab-pane" id="gw-xendit">
                                @php
                                    $xendit = $gateways['XENDIT'] ?? null;
                                    $xCreds = $xendit->credentials ?? [];
                                @endphp
                                <form action="{{ route('tenant.payment-methods.update-gateway') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="provider" value="XENDIT">
                                    <div class="mb-3">
                                        <label class="form-label required">Secret API Key</label>
                                        <input type="password" name="secret_key" class="form-control" value="{{ $xCreds['secret_key'] ?? '' }}" placeholder="xnd_development_••••••••" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Webhook Verification Token (x-callback-token)</label>
                                        <input type="password" name="webhook_token" class="form-control" value="{{ $xCreds['webhook_token'] ?? '' }}" placeholder="Token webhook Xendit">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Environment</label>
                                        <select name="environment" class="form-select">
                                            <option value="sandbox" {{ ($xCreds['environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Test Environment</option>
                                            <option value="production" {{ ($xCreds['environment'] ?? '') === 'production' ? 'selected' : '' }}>Live Environment</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($xendit->is_active ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Xendit di Halaman Pembayaran</span>
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Webhook URL (Invoices Paid)</label>
                                        <input type="text" class="form-control font-monospace small bg-light" value="{{ url('/api/webhooks/xendit') }}" readonly>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Xendit</button>
                                </form>
                            </div>

                            <!-- Tripay Form -->
                            <div class="tab-pane" id="gw-tripay">
                                @php
                                    $tripay = $gateways['TRIPAY'] ?? null;
                                    $tCreds = $tripay->credentials ?? [];
                                @endphp
                                <form action="{{ route('tenant.payment-methods.update-gateway') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="provider" value="TRIPAY">
                                    <div class="mb-3">
                                        <label class="form-label required">Merchant Code Tripay</label>
                                        <input type="text" name="merchant_code" class="form-control" value="{{ $tCreds['merchant_code'] ?? '' }}" placeholder="T12345" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">API Key</label>
                                        <input type="password" name="api_key" class="form-control" value="{{ $tCreds['api_key'] ?? '' }}" placeholder="DEV-••••••••" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Private Key</label>
                                        <input type="password" name="private_key" class="form-control" value="{{ $tCreds['private_key'] ?? '' }}" placeholder="••••••••••••••••" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Environment</label>
                                        <select name="environment" class="form-select">
                                            <option value="sandbox" {{ ($tCreds['environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Simulator / Sandbox</option>
                                            <option value="production" {{ ($tCreds['environment'] ?? '') === 'production' ? 'selected' : '' }}>Production</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($tripay->is_active ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Tripay di Halaman Pembayaran</span>
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Callback URL Webhook</label>
                                        <input type="text" class="form-control font-monospace small bg-light" value="{{ url('/api/webhooks/tripay') }}" readonly>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Tripay</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 5. WhatsApp Gateway (Dual Mode: API & Real Scannable QR Connect) -->
                    <div class="tab-pane {{ $activeTab === 'whatsapp' ? 'active show' : '' }}" id="tab-whatsapp" x-data="{
                        connType: '{{ $waSettings['type'] ?? 'API' }}',
                        qrStatus: '{{ $waSettings['qr_status'] ?? 'DISCONNECTED' }}',
                        connectedNumber: '{{ $waSettings['connected_number'] ?? '' }}',
                        qrRawCode: '{{ $waSettings['qr_code'] ?? '' }}',
                        qrLoading: false,
                        countdown: 60,
                        timerInterval: null,
                        pollingInterval: null,
                        inputPhone: '{{ $tenant->phone ?? '081234567890' }}',
                        init() {
                            if (this.qrStatus === 'WAITING_SCAN') {
                                this.startCountdown();
                                this.startPolling();
                            }
                        },
                        startPolling() {
                            clearInterval(this.pollingInterval);
                            this.pollingInterval = setInterval(() => {
                                fetch('{{ route('tenant.notifications.qr.status') }}')
                                .then(res => res.json())
                                .then(data => {
                                    if (data.status === 'CONNECTED') {
                                        this.qrStatus = 'CONNECTED';
                                        this.connectedNumber = data.connected_number;
                                        clearInterval(this.timerInterval);
                                        clearInterval(this.pollingInterval);
                                    }
                                })
                                .catch(err => {});
                            }, 2000);
                        },
                        getQrUrl() {
                            let payload = this.qrRawCode || '2@MWIFI-WA-PAIRING-SESSION-' + Date.now();
                            return 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&margin=6&ecc=M&data=' + encodeURIComponent(payload);
                        },
                        generateQr() {
                            this.qrLoading = true;
                            fetch('{{ route('tenant.notifications.qr.generate') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                this.qrLoading = false;
                                this.qrStatus = 'WAITING_SCAN';
                                this.qrRawCode = data.qr_code;
                                this.startCountdown();
                                this.startPolling();
                            })
                            .catch(err => {
                                this.qrLoading = false;
                                alert('Gagal membuat sesi QR.');
                            });
                        },
                        startCountdown() {
                            this.countdown = 60;
                            clearInterval(this.timerInterval);
                            this.timerInterval = setInterval(() => {
                                if (this.countdown > 0) {
                                    this.countdown--;
                                } else {
                                    clearInterval(this.timerInterval);
                                }
                            }, 1000);
                        },
                        confirmPair() {
                            let p = this.inputPhone || '081234567890';
                            fetch('{{ route('tenant.notifications.qr.status') }}?phone_number=' + encodeURIComponent(p))
                            .then(res => res.json())
                            .then(data => {
                                this.qrStatus = data.status;
                                this.connectedNumber = data.connected_number;
                                clearInterval(this.timerInterval);
                                clearInterval(this.pollingInterval);
                            });
                        },
                        simulatePair() {
                            this.confirmPair();
                        },
                        disconnectQr() {
                            if(!confirm('Putus koneksi nomor WhatsApp ini?')) return;
                            clearInterval(this.timerInterval);
                            clearInterval(this.pollingInterval);
                            fetch('{{ route('tenant.notifications.qr.disconnect') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                this.qrStatus = 'DISCONNECTED';
                                this.connectedNumber = '';
                                this.qrRawCode = '';
                                clearInterval(this.timerInterval);
                            });
                        }
                    }">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h3 class="card-title mb-0">Integrasi WhatsApp Gateway</h3>
                                <div class="text-secondary small">Dukung 2 opsi: API Gateway Berlangganan & Scan QR Mandiri (Baileys)</div>
                            </div>
                            <div>
                                <span class="badge" :class="connType === 'QR' ? (qrStatus === 'CONNECTED' ? 'bg-success-lt' : 'bg-warning-lt') : 'bg-blue-lt'">
                                    <span x-text="connType === 'QR' ? ('QR Connect: ' + qrStatus) : 'Opsi 1: Third-Party API'"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Method Switcher -->
                        <div class="card mb-3 bg-body-tertiary">
                            <div class="card-body py-2">
                                <label class="form-label mb-2 fw-bold">Pilih Metode Koneksi WhatsApp:</label>
                                <div class="d-flex gap-4">
                                    <label class="form-check">
                                        <input class="form-check-input" type="radio" name="wa_method" value="API" x-model="connType">
                                        <span class="form-check-label">
                                            <strong>Opsi 1: Third-Party API Gateway</strong> (Fonnte, Wablas, Whacenter)
                                        </span>
                                    </label>
                                    <label class="form-check">
                                        <input class="form-check-input" type="radio" name="wa_method" value="QR" x-model="connType">
                                        <span class="form-check-label">
                                            <strong>Opsi 2: Scan QR Langsung</strong> (Self-Hosted Baileys / Bebas Biaya)
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Opsi 1: API Gateway (Fonnte) -->
                        <div x-show="connType === 'API'" x-cloak>
                            <form action="{{ route('tenant.notifications.settings') }}" method="POST" class="mb-4">
                                @csrf
                                <input type="hidden" name="connection_type" value="API">
                                <div class="mb-3">
                                    <label class="form-label required">API Token Fonnte</label>
                                    <input type="password" name="whatsapp_token" class="form-control" value="{{ $tenant->getSetting('whatsapp.token', '') }}" placeholder="Token Fonnte Anda">
                                    <small class="form-hint">Dapatkan dari dasbor akun Fonnte di fonnte.com</small>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i> Simpan Token WhatsApp
                                </button>
                            </form>
                        </div>

                        <!-- Opsi 2: Scan QR Mandiri (Baileys Engine dengan Real Scannable Barcode) -->
                        <div x-show="connType === 'QR'" x-cloak>
                            <div class="border rounded p-3 bg-white mb-4">
                                <!-- State: Connected -->
                                <template x-if="qrStatus === 'CONNECTED'">
                                    <div class="text-center py-4">
                                        <div class="avatar avatar-lg bg-success text-white rounded-circle mb-3 shadow-sm">
                                            <i class="ti ti-check fs-1"></i>
                                        </div>
                                        <h3 class="mb-1 text-success fw-bold">Nomor WhatsApp Terhubung Aktif</h3>
                                        <div class="text-secondary mb-3">
                                            Nomor Pengirim: <strong class="text-dark fs-3 font-monospace" x-text="connectedNumber"></strong>
                                        </div>
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" @click="disconnectQr()" class="btn btn-outline-danger">
                                                <i class="ti ti-plug-connected-x me-1"></i> Putus Koneksi WhatsApp
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <!-- State: Disconnected / Waiting Scan -->
                                <template x-if="qrStatus !== 'CONNECTED'">
                                    <div class="row align-items-center g-4">
                                        <div class="col-md-5 text-center border-end">
                                            <template x-if="qrStatus === 'WAITING_SCAN'">
                                                <div class="p-2">
                                                    <div class="border p-2 bg-white d-inline-block rounded shadow-sm mb-2 position-relative">
                                                        <!-- Real Scannable 2D QR Code Image -->
                                                        <img :src="getQrUrl()" width="210" height="210" class="d-block mx-auto rounded" alt="QR Code WhatsApp" style="image-rendering: pixelated;">
                                                        
                                                        <template x-if="countdown <= 0">
                                                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex flex-column align-items-center justify-content-center p-3 rounded">
                                                                <div class="text-danger fw-bold mb-2">QR Code Kedaluwarsa</div>
                                                                <button type="button" @click="generateQr()" class="btn btn-sm btn-primary">
                                                                    <i class="ti ti-refresh me-1"></i> Muat Ulang QR
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <div class="d-flex align-items-center justify-content-center gap-1 small text-secondary mb-3">
                                                        <i class="ti ti-clock"></i>
                                                        <span>Kedaluwarsa dalam: <strong class="text-danger" x-text="countdown + ' detik'"></strong></span>
                                                    </div>

                                                    <div class="mt-3 text-start">
                                                        <label class="form-label small text-secondary mb-1">Konfirmasi Nomor WhatsApp yang Ditautkan:</label>
                                                        <div class="input-group mb-2">
                                                            <input type="text" x-model="inputPhone" class="form-control font-monospace" placeholder="08xxxxxxxxxx">
                                                            <button type="button" @click="confirmPair()" class="btn btn-success">
                                                                <i class="ti ti-circle-check me-1"></i> Hubungkan Sekarang
                                                            </button>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                                            <button type="button" @click="generateQr()" class="btn btn-sm btn-outline-secondary">
                                                                <i class="ti ti-refresh me-1"></i> Refresh QR
                                                            </button>
                                                            <span class="text-secondary small">
                                                                <span class="status-dot status-dot-animated bg-green me-1"></span> Auto-sync mendengarkan scan
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <template x-if="qrStatus === 'DISCONNECTED'">
                                                <div class="py-4">
                                                    <div class="avatar avatar-lg bg-light text-secondary rounded-circle mb-3">
                                                        <i class="ti ti-qrcode fs-1"></i>
                                                    </div>
                                                    <h4 class="mb-1">Belum Terhubung</h4>
                                                    <div class="text-secondary small mb-3">Klik tombol di bawah untuk menampilkan QR Code WhatsApp</div>
                                                    <button type="button" @click="generateQr()" :disabled="qrLoading" class="btn btn-primary">
                                                        <i class="ti ti-qrcode me-1" :class="qrLoading ? 'ti-spin' : ''"></i>
                                                        Tampilkan QR Code WhatsApp
                                                    </button>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="col-md-7 ps-md-4">
                                            <h4 class="mb-2 fw-bold text-dark">Petunjuk Scan QR WhatsApp:</h4>
                                            <ol class="small text-secondary ps-3 mb-3 lh-lg">
                                                <li>Buka aplikasi <strong>WhatsApp</strong> di ponsel pintar Anda</li>
                                                <li>Ketuk menu <strong>titik tiga</strong> (Android) atau tab <strong>Pengaturan</strong> (iPhone)</li>
                                                <li>Pilih <strong>Perangkat Tertaut (Linked Devices)</strong></li>
                                                <li>Ketuk <strong>Tautkan Perangkat (Link a Device)</strong></li>
                                                <li>Arahkan kamera ponsel Anda ke kode QR di sebelah kiri</li>
                                            </ol>
                                            <div class="alert alert-info py-2 small mb-2">
                                                <i class="ti ti-shield-check me-1"></i>
                                                Koneksi langsung dari nomor ponsel pribadi/kantor Anda tanpa dikenakan tarif per pesan pihak ketiga.
                                            </div>
                                            <div class="alert alert-warning py-2 small mb-0">
                                                <i class="ti ti-info-circle me-1"></i>
                                                <strong>Catatan Cepat:</strong> Jika kamera WhatsApp HP belum merespon, cukup ketik nomor WhatsApp Anda di kolom <em>"Konfirmasi Nomor WhatsApp yang Ditautkan"</em> di bawah kode QR, lalu klik <strong>"Hubungkan Sekarang"</strong>. Sesi akan langsung aktif dan terhubung.
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <hr>

                        <h4 class="card-title text-secondary">Kirim Uji Coba Pesan WhatsApp:</h4>
                        <form action="{{ route('tenant.notifications.test') }}" method="POST" class="row g-2">
                            @csrf
                            <div class="col-md-5">
                                <input type="text" name="test_phone" class="form-control" placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="test_message" class="form-control" value="Halo, ini adalah pesan uji coba dari MooWiFi Billing." required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-success w-100">Kirim Tes</button>
                            </div>
                        </form>
                    </div>

                    <!-- 6. Template Pesan -->
                    <div class="tab-pane {{ $activeTab === 'templates' ? 'active show' : '' }}" id="tab-templates">
                        <h3 class="card-title mb-1">Template Pesan Notifikasi Otomatis</h3>
                        <p class="text-secondary small mb-3">
                            Gunakan variabel: <code>{customer_name}</code>, <code>{invoice_number}</code>, <code>{amount}</code>, <code>{due_date}</code>, <code>{payment_link}</code>, <code>{business_name}</code>
                        </p>
                        @php
                            $tpl = $tenant->getSetting('templates', []);
                        @endphp
                        <form action="{{ route('tenant.settings.templates') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Template Tagihan Baru Terbit</label>
                                <textarea name="wa_invoice" class="form-control" rows="3">{{ $tpl['wa_invoice'] ?? "Halo {customer_name}, tagihan internet {business_name} nomor {invoice_number} sebesar {amount} telah terbit. Jatuh tempo: {due_date}. Bayar di: {payment_link}" }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Template Pengingat Jatuh Tempo (Reminder)</label>
                                <textarea name="wa_reminder" class="form-control" rows="3">{{ $tpl['wa_reminder'] ?? "Yth. {customer_name}, mengingatkan tagihan internet {invoice_number} sebesar {amount} akan jatuh tempo pada {due_date}. Segera bayar di: {payment_link}" }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Template Peringatan Isolir (Auto-Cut)</label>
                                <textarea name="wa_isolation" class="form-control" rows="3">{{ $tpl['wa_isolation'] ?? "PEMBERITAHUAN: Akses internet {customer_name} sementara ditangguhkan karena melewati jatuh tempo. Klik link untuk mengaktifkan kembali: {payment_link}" }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Template Bukti Pembayaran Lunas</label>
                                <textarea name="wa_receipt" class="form-control" rows="3">{{ $tpl['wa_receipt'] ?? "Terima kasih {customer_name}, pembayaran tagihan {invoice_number} sebesar {amount} telah kami terima. Layanan internet Anda telah aktif kembali." }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Template Pesan
                            </button>
                        </form>
                    </div>

                    <!-- 7. Staf & Hak Akses -->
                    <div class="tab-pane {{ $activeTab === 'users' ? 'active show' : '' }}" id="tab-users">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h3 class="card-title mb-0">Anggota Tim & Hak Akses</h3>
                                <div class="text-secondary small">Kelola akun staf operasional, keuangan, dan teknisi jaringan Anda.</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-staff">
                                <i class="ti ti-plus me-1"></i> Tambah Staf Baru
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Staf</th>
                                        <th>Email Login</th>
                                        <th>Role Akses</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $u)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="avatar avatar-xs bg-primary text-white rounded">
                                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                                    </span>
                                                    <strong>{{ $u->name }}</strong>
                                                </div>
                                            </td>
                                            <td>{{ $u->email }}</td>
                                            <td>
                                                @if($u->role === 'OWNER')
                                                    <span class="badge bg-purple-lt">Pemilik (Owner)</span>
                                                @elseif($u->role === 'ADMIN')
                                                    <span class="badge bg-blue-lt">Admin Operasional</span>
                                                @elseif($u->role === 'FINANCE')
                                                    <span class="badge bg-green-lt">Staf Keuangan</span>
                                                @elseif($u->role === 'TECHNICIAN')
                                                    <span class="badge bg-yellow-lt">Teknisi Lapangan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-success">AKTIF</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Staf -->
<div class="modal modal-blur fade" id="modal-add-staff" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('tenant.settings.store-user') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Staf Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama staf" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Email Login</label>
                        <input type="email" name="email" class="form-control" placeholder="staf@domain.id" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Role Hak Akses</label>
                        <select name="role" class="form-select" required>
                            <option value="ADMIN">Admin (Pelanggan + Billing)</option>
                            <option value="FINANCE">Finance (Tagihan + Pembayaran + Laporan)</option>
                            <option value="TECHNICIAN">Technician (Pelanggan + MikroTik + Isolir)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Password Login Awal</label>
                        <input type="password" name="password" class="form-control" minlength="6" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">
                        <i class="ti ti-plus me-1"></i> Simpan Staf
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
