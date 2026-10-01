@extends('layouts.tenant')

@section('title', 'Log Notifikasi WhatsApp & Email')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Riwayat Pengiriman</div>
                <h2 class="page-title">Log Notifikasi WhatsApp & Email</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.notifications.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-brand-whatsapp me-1"></i> Gateway WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('tenant.notifications.logs') }}" class="row g-2">
                    <div class="col-md-4">
                        <select name="channel" class="form-select">
                            <option value="">Semua Saluran (WhatsApp & Email)</option>
                            <option value="WHATSAPP" {{ request('channel') === 'WHATSAPP' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="EMAIL" {{ request('channel') === 'EMAIL' ? 'selected' : '' }}>Email</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">Semua Status Pengiriman</option>
                            <option value="SENT" {{ request('status') === 'SENT' ? 'selected' : '' }}>Terkirim (SENT)</option>
                            <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>Gagal (FAILED)</option>
                            <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Menunggu (PENDING)</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="ti ti-filter me-1"></i> Filter Log
                        </button>
                        <a href="{{ route('tenant.notifications.logs') }}" class="btn btn-ghost-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Waktu Kirim</th>
                            <th>Saluran</th>
                            <th>Penerima & Pelanggan</th>
                            <th>Pesan / Subjek</th>
                            <th>Provider</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-secondary small font-monospace">
                                    {{ $log->sent_at ? $log->sent_at->format('d/m/Y H:i:s') : $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td>
                                    @if($log->channel === 'WHATSAPP')
                                        <span class="badge bg-green-lt">
                                            <i class="ti ti-brand-whatsapp me-1"></i> WhatsApp
                                        </span>
                                    @else
                                        <span class="badge bg-blue-lt">
                                            <i class="ti ti-mail me-1"></i> Email
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $log->recipient }}</div>
                                    <div class="text-secondary small">{{ $log->customer->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 320px;" title="{{ $log->message }}">
                                        {{ $log->message }}
                                    </div>
                                    @if($log->error)
                                        <div class="text-danger small mt-1">
                                            <i class="ti ti-alert-circle me-1"></i> {{ $log->error }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary-lt">{{ $log->provider ?? 'SYSTEM' }}</span>
                                </td>
                                <td>
                                    @if($log->status === 'SENT')
                                        <span class="badge bg-success-lt">TERKIRIM</span>
                                    @elseif($log->status === 'FAILED')
                                        <span class="badge bg-danger-lt">GAGAL</span>
                                    @else
                                        <span class="badge bg-warning-lt">{{ $log->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <x-empty-state 
                                        title="Belum Ada Log Notifikasi" 
                                        subtitle="Setiap pesan invoice atau pengingat WhatsApp dan Email yang dikirim akan tersimpan di sini."
                                    />
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
