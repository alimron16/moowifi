@extends('layouts.tenant')

@section('title', 'Konfigurasi Multi Payment Gateway')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Otomatisasi Pembayaran Multi-Provider</div>
                <h2 class="page-title">Integrasi Payment Gateway</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tab-duitku" class="nav-link active d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="true" role="tab">
                            <span class="status-dot {{ isset($gateways['DUITKU']) && $gateways['DUITKU']->is_active ? 'status-dot-animated bg-success' : 'bg-secondary' }}"></span>
                            Duitku
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-midtrans" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                            <span class="status-dot {{ isset($gateways['MIDTRANS']) && $gateways['MIDTRANS']->is_active ? 'status-dot-animated bg-success' : 'bg-secondary' }}"></span>
                            Midtrans (Snap)
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-xendit" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                            <span class="status-dot {{ isset($gateways['XENDIT']) && $gateways['XENDIT']->is_active ? 'status-dot-animated bg-success' : 'bg-secondary' }}"></span>
                            Xendit
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-tripay" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                            <span class="status-dot {{ isset($gateways['TRIPAY']) && $gateways['TRIPAY']->is_active ? 'status-dot-animated bg-success' : 'bg-secondary' }}"></span>
                            Tripay
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- 1. Duitku Gateway -->
                    <div class="tab-pane active show" id="tab-duitku" role="tabpanel">
                        @php
                            $duitku = $gateways['DUITKU'] ?? null;
                            $dCreds = $duitku->credentials ?? [];
                        @endphp
                        <div class="row row-cards">
                            <div class="col-lg-7">
                                <form action="{{ route('tenant.payment-methods.update-gateway') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="provider" value="DUITKU">
                                    <div class="mb-3">
                                        <label class="form-label required">Merchant Code Duitku</label>
                                        <input type="text" name="merchant_code" class="form-control" value="{{ $dCreds['merchant_code'] ?? '' }}" placeholder="D12345" required>
                                        <small class="form-hint">Dapat diperoleh dari menu Profil Proyek di portal merchant Duitku.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">API Key (Merchant Key)</label>
                                        <input type="password" name="api_key" class="form-control" value="{{ $dCreds['api_key'] ?? '' }}" placeholder="••••••••••••••••" required>
                                        <small class="form-hint">Kredensial disimpan terenkripsi (AES-256).</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Environment</label>
                                        <select name="environment" class="form-select">
                                            <option value="sandbox" {{ ($dCreds['environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox (Mode Simulasi Pengujian)</option>
                                            <option value="production" {{ ($dCreds['environment'] ?? '') === 'production' ? 'selected' : '' }}>Production (Mode Nyata)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($duitku->is_active ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Duitku di Halaman Pembayaran Pelanggan</span>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Konfigurasi Duitku
                                    </button>
                                </form>
                            </div>
                            <div class="col-lg-5">
                                <div class="card bg-body-tertiary">
                                    <div class="card-body">
                                        <div class="fw-bold mb-2">Callback URL (Webhook) Duitku:</div>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control font-monospace small" value="{{ url('/api/webhooks/duitku') }}" id="whDuitku" readonly>
                                            <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('whDuitku').value); alert('URL disalin!');">
                                                <i class="ti ti-copy"></i>
                                            </button>
                                        </div>
                                        <div class="text-secondary small">Tempelkan URL ini di pengaturan proyek Duitku Anda.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Midtrans Gateway -->
                    <div class="tab-pane" id="tab-midtrans" role="tabpanel">
                        @php
                            $midtrans = $gateways['MIDTRANS'] ?? null;
                            $mCreds = $midtrans->credentials ?? [];
                        @endphp
                        <div class="row row-cards">
                            <div class="col-lg-7">
                                <form action="{{ route('tenant.payment-methods.update-gateway') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="provider" value="MIDTRANS">
                                    <div class="mb-3">
                                        <label class="form-label required">Server Key</label>
                                        <input type="password" name="server_key" class="form-control" value="{{ $mCreds['server_key'] ?? '' }}" placeholder="SB-Mid-server-••••••••" required>
                                        <small class="form-hint">Dapatkan dari menu Settings &rarr; Access Keys di portal Midtrans.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Client Key</label>
                                        <input type="text" name="client_key" class="form-control" value="{{ $mCreds['client_key'] ?? '' }}" placeholder="SB-Mid-client-••••••••">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Environment</label>
                                        <select name="environment" class="form-select">
                                            <option value="sandbox" {{ ($mCreds['environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox (Mode Simulasi Pengujian)</option>
                                            <option value="production" {{ ($mCreds['environment'] ?? '') === 'production' ? 'selected' : '' }}>Production (Mode Nyata)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($midtrans->is_active ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Midtrans di Halaman Pembayaran Pelanggan</span>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Konfigurasi Midtrans
                                    </button>
                                </form>
                            </div>
                            <div class="col-lg-5">
                                <div class="card bg-body-tertiary">
                                    <div class="card-body">
                                        <div class="fw-bold mb-2">Notification URL (Webhook) Midtrans:</div>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control font-monospace small" value="{{ url('/api/webhooks/midtrans') }}" id="whMidtrans" readonly>
                                            <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('whMidtrans').value); alert('URL disalin!');">
                                                <i class="ti ti-copy"></i>
                                            </button>
                                        </div>
                                        <div class="text-secondary small">Tempelkan URL ini di Settings &rarr; Configuration &rarr; Payment Notification URL di Midtrans.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Xendit Gateway -->
                    <div class="tab-pane" id="tab-xendit" role="tabpanel">
                        @php
                            $xendit = $gateways['XENDIT'] ?? null;
                            $xCreds = $xendit->credentials ?? [];
                        @endphp
                        <div class="row row-cards">
                            <div class="col-lg-7">
                                <form action="{{ route('tenant.payment-methods.update-gateway') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="provider" value="XENDIT">
                                    <div class="mb-3">
                                        <label class="form-label required">Secret API Key Xendit</label>
                                        <input type="password" name="secret_key" class="form-control" value="{{ $xCreds['secret_key'] ?? '' }}" placeholder="xnd_development_••••••••" required>
                                        <small class="form-hint">Dapatkan dari menu Settings &rarr; API Keys di Dashboard Xendit.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Webhook Verification Token (x-callback-token)</label>
                                        <input type="password" name="webhook_token" class="form-control" value="{{ $xCreds['webhook_token'] ?? '' }}" placeholder="Token verifikasi webhook Anda">
                                        <small class="form-hint">Dapat dilihat di menu Settings &rarr; Webhooks di Dashboard Xendit.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Environment</label>
                                        <select name="environment" class="form-select">
                                            <option value="sandbox" {{ ($xCreds['environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Test Environment (Mode Pengujian)</option>
                                            <option value="production" {{ ($xCreds['environment'] ?? '') === 'production' ? 'selected' : '' }}>Live Environment (Mode Nyata)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ ($xendit->is_active ?? false) ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Xendit di Halaman Pembayaran Pelanggan</span>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Konfigurasi Xendit
                                    </button>
                                </form>
                            </div>
                            <div class="col-lg-5">
                                <div class="card bg-body-tertiary">
                                    <div class="card-body">
                                        <div class="fw-bold mb-2">Webhook URL (Invoices Paid) Xendit:</div>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control font-monospace small" value="{{ url('/api/webhooks/xendit') }}" id="whXendit" readonly>
                                            <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('whXendit').value); alert('URL disalin!');">
                                                <i class="ti ti-copy"></i>
                                            </button>
                                        </div>
                                        <div class="text-secondary small">Masukkan URL ini di menu Webhooks &rarr; Invoices Paid pada Dashboard Xendit.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Tripay Gateway -->
                    <div class="tab-pane" id="tab-tripay" role="tabpanel">
                        @php
                            $tripay = $gateways['TRIPAY'] ?? null;
                            $tCreds = $tripay->credentials ?? [];
                        @endphp
                        <div class="row row-cards">
                            <div class="col-lg-7">
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
                                        <small class="form-hint">Digunakan untuk validasi signature HMAC-SHA256.</small>
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
                                            <span class="form-check-label">Aktifkan Tripay di Halaman Pembayaran Pelanggan</span>
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Konfigurasi Tripay
                                    </button>
                                </form>
                            </div>
                            <div class="col-lg-5">
                                <div class="card bg-body-tertiary">
                                    <div class="card-body">
                                        <div class="fw-bold mb-2">Callback URL (Webhook) Tripay:</div>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control font-monospace small" value="{{ url('/api/webhooks/tripay') }}" id="whTripay" readonly>
                                            <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('whTripay').value); alert('URL disalin!');">
                                                <i class="ti ti-copy"></i>
                                            </button>
                                        </div>
                                        <div class="text-secondary small">Tempelkan URL ini di menu Merchant &rarr; Edit &rarr; URL Callback pada portal Tripay.</div>
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
@endsection
