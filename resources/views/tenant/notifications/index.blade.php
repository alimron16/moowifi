@extends('layouts.tenant')

@section('title', 'Notifikasi WhatsApp')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Pusat Pesan</div>
                <h2 class="page-title">Notifikasi WhatsApp (API & Scan QR)</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.settings.index', ['tab' => 'whatsapp']) }}" class="btn btn-outline-primary">
                    <i class="ti ti-settings me-1"></i> Pengaturan WhatsApp Gateway
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards mb-4">
            <!-- Connection Status Card -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Metode & Status Koneksi WhatsApp</h3>
                        <div class="card-actions">
                            @if(($waSettings['type'] ?? 'API') === 'QR')
                                <span class="badge {{ ($waSettings['qr_status'] ?? '') === 'CONNECTED' ? 'bg-success' : 'bg-warning' }}">
                                    QR Connect: {{ $waSettings['qr_status'] ?? 'DISCONNECTED' }}
                                </span>
                            @else
                                <span class="badge {{ !empty($waSettings['token']) ? 'bg-success' : 'bg-secondary' }}">
                                    API Gateway (Fonnte)
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if(($waSettings['type'] ?? 'API') === 'QR')
                            <div class="mb-3">
                                <label class="form-label">Metode Aktif</label>
                                <div class="form-control-plaintext fw-bold">Opsi 2: Scan QR Langsung (Baileys Engine)</div>
                            </div>
                            @if(($waSettings['qr_status'] ?? '') === 'CONNECTED')
                                <div class="alert alert-success py-2 small mb-3">
                                    <i class="ti ti-circle-check me-1"></i> Terhubung ke nomor: <strong>{{ $waSettings['connected_number'] ?? '-' }}</strong>
                                </div>
                                <form action="{{ route('tenant.notifications.qr.disconnect') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Putus koneksi WhatsApp?')">
                                        <i class="ti ti-plug-connected-x me-1"></i> Putus Sesi WhatsApp
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning py-2 small mb-3">
                                    <i class="ti ti-alert-triangle me-1"></i> Sesi QR belum terhubung. Silakan lakukan scan QR di Pusat Pengaturan.
                                </div>
                                <a href="{{ route('tenant.settings.index', ['tab' => 'whatsapp']) }}" class="btn btn-sm btn-primary">
                                    <i class="ti ti-qrcode me-1"></i> Buka Layar Scan QR
                                </a>
                            @endif
                        @else
                            <div class="mb-3">
                                <label class="form-label">Metode Aktif</label>
                                <div class="form-control-plaintext fw-bold">Opsi 1: Third-Party API Gateway (Fonnte)</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Token API Terdaftar</label>
                                <div class="form-control-plaintext font-monospace">
                                    {{ !empty($waSettings['token']) ? substr($waSettings['token'], 0, 8) . '••••••••' : '(Belum Disimpan)' }}
                                </div>
                            </div>
                            <a href="{{ route('tenant.settings.index', ['tab' => 'whatsapp']) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-edit me-1"></i> Ubah Token / Ganti ke Mode Scan QR
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Test Message Card -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Kirim Pesan Uji Coba</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tenant.notifications.test') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label required">Nomor WhatsApp Tujuan</label>
                                <input type="text" name="test_phone" class="form-control" placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Isi Pesan</label>
                                <input type="text" name="test_message" class="form-control" value="Halo, ini adalah pesan uji coba dari MooWiFi Billing." required>
                            </div>
                            <button type="submit" class="btn btn-outline-success">
                                <i class="ti ti-send me-1"></i> Kirim Pesan Tes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Logs Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Log Notifikasi Terkirim</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Penerima</th>
                            <th>Kanal</th>
                            <th>Pesan</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-secondary small">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <strong>{{ $log->recipient }}</strong>
                                    @if($log->customer)
                                        <div class="small text-secondary">{{ $log->customer->name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-blue-lt">{{ $log->channel }} ({{ $log->provider ?? 'GATEWAY' }})</span>
                                </td>
                                <td class="text-truncate" style="max-width: 320px;">
                                    {{ $log->message ?? $log->content }}
                                </td>
                                <td class="text-center">
                                    <x-badge :status="$log->status" :dot="true" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-secondary">
                                    Belum ada log notifikasi WhatsApp yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="card-footer d-flex align-items-center">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
