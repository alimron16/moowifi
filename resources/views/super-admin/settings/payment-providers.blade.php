@extends('layouts.super-admin')

@section('title', 'Platform Payment Providers')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Penerimaan Langganan Platform</div>
                <h2 class="page-title">Payment Provider Platform (Rekening SaaS)</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="alert alert-info d-flex align-items-center mb-3">
            <i class="ti ti-info-circle fs-2 me-2"></i>
            <div>
                <strong>Konsep Rekening Platform SaaS:</strong><br>
                Gateway & Rekening di halaman ini adalah milik <strong>Super Admin (Pemilik Platform)</strong> untuk menerima setoran <strong>biaya langganan SaaS</strong> bulanan/tahunan dari para pemilik RT/RW Net. Berbeda dengan gateway tenant (yang digunakan tenant untuk menarik tagihan dari warga).
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tab-duitku" class="nav-link active d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="true" role="tab">
                            <span class="status-dot {{ ($gateways['DUITKU']['is_active'] ?? false) ? 'status-dot-animated bg-success' : 'bg-secondary' }}"></span>
                            Duitku Platform
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-midtrans" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                            <span class="status-dot {{ ($gateways['MIDTRANS']['is_active'] ?? false) ? 'status-dot-animated bg-success' : 'bg-secondary' }}"></span>
                            Midtrans Platform
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-xendit" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                            <span class="status-dot {{ ($gateways['XENDIT']['is_active'] ?? false) ? 'status-dot-animated bg-success' : 'bg-secondary' }}"></span>
                            Xendit Platform
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-tripay" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                            <span class="status-dot {{ ($gateways['TRIPAY']['is_active'] ?? false) ? 'status-dot-animated bg-success' : 'bg-secondary' }}"></span>
                            Tripay Platform
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-manual" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                            <i class="ti ti-building-bank me-1"></i>
                            Transfer Bank Manual
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- 1. Duitku -->
                    <div class="tab-pane active show" id="tab-duitku" role="tabpanel">
                        <form action="{{ route('super-admin.payment-providers.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="provider" value="DUITKU">
                            <div class="row row-cards">
                                <div class="col-lg-7">
                                    <div class="mb-3">
                                        <label class="form-label required">Merchant Code Duitku Platform</label>
                                        <input type="text" name="merchant_code" class="form-control" value="{{ $gateways['DUITKU']['merchant_code'] ?? '' }}" placeholder="D12345" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">API Key (Merchant Key)</label>
                                        <input type="password" name="api_key" class="form-control" value="{{ $gateways['DUITKU']['api_key'] ?? '' }}" placeholder="••••••••••••••••" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Environment</label>
                                        <select name="environment" class="form-select">
                                            <option value="sandbox" {{ ($gateways['DUITKU']['environment'] ?? '') === 'sandbox' ? 'selected' : '' }}>Sandbox (Mode Pengujian)</option>
                                            <option value="production" {{ ($gateways['DUITKU']['environment'] ?? '') === 'production' ? 'selected' : '' }}>Production (Mode Nyata)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($gateways['DUITKU']['is_active'] ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Duitku untuk Penerimaan Langganan SaaS</span>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Duitku Platform
                                    </button>
                                </div>
                                <div class="col-lg-5">
                                    <div class="card bg-body-tertiary">
                                        <div class="card-body">
                                            <div class="fw-bold mb-1">Callback URL Duitku Platform:</div>
                                            <input type="text" class="form-control font-monospace small mb-2" value="{{ url('/api/webhooks/duitku') }}" readonly>
                                            <small class="text-secondary">Daftarkan di portal Duitku akun platform Anda.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- 2. Midtrans -->
                    <div class="tab-pane" id="tab-midtrans" role="tabpanel">
                        <form action="{{ route('super-admin.payment-providers.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="provider" value="MIDTRANS">
                            <div class="row row-cards">
                                <div class="col-lg-7">
                                    <div class="mb-3">
                                        <label class="form-label required">Server Key Midtrans Platform</label>
                                        <input type="password" name="server_key" class="form-control" value="{{ $gateways['MIDTRANS']['server_key'] ?? '' }}" placeholder="SB-Mid-server-••••••••" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Client Key</label>
                                        <input type="text" name="client_key" class="form-control" value="{{ $gateways['MIDTRANS']['client_key'] ?? '' }}" placeholder="SB-Mid-client-••••••••">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Environment</label>
                                        <select name="environment" class="form-select">
                                            <option value="sandbox" {{ ($gateways['MIDTRANS']['environment'] ?? '') === 'sandbox' ? 'selected' : '' }}>Sandbox (Mode Pengujian)</option>
                                            <option value="production" {{ ($gateways['MIDTRANS']['environment'] ?? '') === 'production' ? 'selected' : '' }}>Production (Mode Nyata)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($gateways['MIDTRANS']['is_active'] ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Midtrans untuk Penerimaan Langganan SaaS</span>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Midtrans Platform
                                    </button>
                                </div>
                                <div class="col-lg-5">
                                    <div class="card bg-body-tertiary">
                                        <div class="card-body">
                                            <div class="fw-bold mb-1">Notification URL Midtrans Platform:</div>
                                            <input type="text" class="form-control font-monospace small mb-2" value="{{ url('/api/webhooks/midtrans') }}" readonly>
                                            <small class="text-secondary">Daftarkan di portal Midtrans akun platform Anda.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- 3. Xendit -->
                    <div class="tab-pane" id="tab-xendit" role="tabpanel">
                        <form action="{{ route('super-admin.payment-providers.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="provider" value="XENDIT">
                            <div class="row row-cards">
                                <div class="col-lg-7">
                                    <div class="mb-3">
                                        <label class="form-label required">Secret API Key Xendit</label>
                                        <input type="password" name="secret_key" class="form-control" value="{{ $gateways['XENDIT']['secret_key'] ?? '' }}" placeholder="xnd_development_••••••••" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Webhook Verification Token (x-callback-token)</label>
                                        <input type="password" name="webhook_token" class="form-control" value="{{ $gateways['XENDIT']['webhook_token'] ?? '' }}" placeholder="Token webhook Xendit">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Environment</label>
                                        <select name="environment" class="form-select">
                                            <option value="sandbox" {{ ($gateways['XENDIT']['environment'] ?? '') === 'sandbox' ? 'selected' : '' }}>Sandbox (Test Mode)</option>
                                            <option value="production" {{ ($gateways['XENDIT']['environment'] ?? '') === 'production' ? 'selected' : '' }}>Production (Live Mode)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($gateways['XENDIT']['is_active'] ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Xendit untuk Penerimaan Langganan SaaS</span>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Xendit Platform
                                    </button>
                                </div>
                                <div class="col-lg-5">
                                    <div class="card bg-body-tertiary">
                                        <div class="card-body">
                                            <div class="fw-bold mb-1">Webhook URL Xendit Platform:</div>
                                            <input type="text" class="form-control font-monospace small mb-2" value="{{ url('/api/webhooks/xendit') }}" readonly>
                                            <small class="text-secondary">Daftarkan di portal Xendit akun platform Anda.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- 4. Tripay -->
                    <div class="tab-pane" id="tab-tripay" role="tabpanel">
                        <form action="{{ route('super-admin.payment-providers.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="provider" value="TRIPAY">
                            <div class="row row-cards">
                                <div class="col-lg-7">
                                    <div class="mb-3">
                                        <label class="form-label required">Merchant Code Tripay</label>
                                        <input type="text" name="merchant_code" class="form-control" value="{{ $gateways['TRIPAY']['merchant_code'] ?? '' }}" placeholder="T12345" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">API Key</label>
                                        <input type="password" name="api_key" class="form-control" value="{{ $gateways['TRIPAY']['api_key'] ?? '' }}" placeholder="DEV-••••••••" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Private Key</label>
                                        <input type="password" name="private_key" class="form-control" value="{{ $gateways['TRIPAY']['private_key'] ?? '' }}" placeholder="••••••••••••••••" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Environment</label>
                                        <select name="environment" class="form-select">
                                            <option value="sandbox" {{ ($gateways['TRIPAY']['environment'] ?? '') === 'sandbox' ? 'selected' : '' }}>Sandbox (Mode Pengujian)</option>
                                            <option value="production" {{ ($gateways['TRIPAY']['environment'] ?? '') === 'production' ? 'selected' : '' }}>Production (Mode Nyata)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($gateways['TRIPAY']['is_active'] ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Tripay untuk Penerimaan Langganan SaaS</span>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Tripay Platform
                                    </button>
                                </div>
                                <div class="col-lg-5">
                                    <div class="card bg-body-tertiary">
                                        <div class="card-body">
                                            <div class="fw-bold mb-1">Callback URL Tripay Platform:</div>
                                            <input type="text" class="form-control font-monospace small mb-2" value="{{ url('/api/webhooks/tripay') }}" readonly>
                                            <small class="text-secondary">Daftarkan di portal Tripay akun platform Anda.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- 5. Transfer Bank Manual Platform -->
                    <div class="tab-pane" id="tab-manual" role="tabpanel">
                        <form action="{{ route('super-admin.payment-providers.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="update_type" value="manual_bank">
                            <p class="text-secondary">
                                Rekening ini akan ditampilkan kepada pemilik RT/RW Net saat mereka memilih metode <strong>Transfer Manual</strong> untuk membayar invoice paket langganan MooWiFi SaaS.
                            </p>
                            @php
                                $primaryBank = $manualBanks[0] ?? ['bank_name' => 'BCA', 'account_number' => '', 'account_name' => '', 'instructions' => ''];
                            @endphp
                            <div class="row row-cards">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label required">Nama Bank</label>
                                        <input type="text" name="bank_names[]" class="form-control" value="{{ $primaryBank['bank_name'] ?? 'BCA' }}" placeholder="BCA / Mandiri / BRI" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label required">Nomor Rekening Platform</label>
                                        <input type="text" name="account_numbers[]" class="form-control" value="{{ $primaryBank['account_number'] ?? '' }}" placeholder="1234567890" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label required">Atas Nama Rekening</label>
                                        <input type="text" name="account_names[]" class="form-control" value="{{ $primaryBank['account_name'] ?? '' }}" placeholder="PT MooWiFi Digital Indonesia" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Instruksi Pembayaran Bagi Tenant</label>
                                        <textarea name="instructions[]" class="form-control" rows="3" placeholder="Contoh: Cantumkan ID Tenant pada berita transfer. Konfirmasi bukti transfer via WhatsApp Admin.">{{ $primaryBank['instructions'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Rekening Platform
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
