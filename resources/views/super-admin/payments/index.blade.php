@extends('layouts.super-admin')

@section('title', 'Riwayat Pembayaran Langganan SaaS')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Penerimaan Platform</div>
                <h2 class="page-title">Pembayaran Langganan SaaS (Tenant ke Platform)</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('super-admin.subscriptions.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-layers-linked me-1"></i> Data Langganan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>No. Referensi</th>
                            <th>Tenant</th>
                            <th>Paket Langganan</th>
                            <th>Nilai Tagihan SaaS</th>
                            <th>Masa Aktif Paket</th>
                            <th>Status Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $sub)
                            <tr>
                                <td class="font-monospace small fw-bold">
                                    SUB-PAY-{{ str_pad($sub->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $sub->tenant->name ?? 'Tenant' }}</div>
                                    <div class="text-secondary small">{{ $sub->tenant->code ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-purple-lt">{{ $sub->saasPlan->name ?? 'Standard' }}</span>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($sub->saasPlan?->price ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-secondary small font-monospace">
                                    {{ $sub->starts_at ? $sub->starts_at->format('d/m/Y') : '-' }} s/d {{ $sub->ends_at ? $sub->ends_at->format('d/m/Y') : '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-success-lt">LUNAS (SETTLED)</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <x-empty-state 
                                        title="Belum Ada Transaksi" 
                                        subtitle="Riwayat pembayaran langganan SaaS dari seluruh pemilik RT/RW Net akan tampil di sini."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($subscriptions->hasPages())
                <div class="card-footer d-flex align-items-center">
                    {{ $subscriptions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
