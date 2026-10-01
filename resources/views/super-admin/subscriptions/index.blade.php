@extends('layouts.super-admin')

@section('title', 'Langganan Tenant SaaS')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">SaaS Recurring Revenue</div>
                <h2 class="page-title">Langganan Tenant RT/RW Net</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-sub">
                    <i class="ti ti-plus me-1"></i> Terbitkan Langganan Baru
                </button>
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
                            <th>Tenant</th>
                            <th>Paket SaaS</th>
                            <th>Harga Paket</th>
                            <th>Mulai Berlaku</th>
                            <th>Berakhir Pada</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $sub)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $sub->tenant->name ?? 'Tenant Terhapus' }}</div>
                                    <div class="text-secondary small">Kode: {{ $sub->tenant->code ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-purple-lt">{{ $sub->saasPlan->name ?? 'Paket Kustom' }}</span>
                                </td>
                                <td class="fw-bold">
                                    Rp {{ number_format($sub->saasPlan?->price ?? 0, 0, ',', '.') }}/bln
                                </td>
                                <td class="text-secondary small font-monospace">
                                    {{ $sub->starts_at ? $sub->starts_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="text-secondary small font-monospace">
                                    {{ $sub->ends_at ? $sub->ends_at->format('d/m/Y') : '-' }}
                                </td>
                                <td>
                                    <x-badge :status="$sub->status" :dot="true" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <x-empty-state 
                                        title="Belum Ada Langganan" 
                                        subtitle="Terbitkan paket langganan bagi tenant untuk mengaktifkan akses platform."
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

<!-- Modal Add Subscription -->
<div class="modal modal-blur fade" id="modal-add-sub" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('super-admin.subscriptions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Aktifkan Langganan Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Pilih Tenant RT/RW Net</label>
                        <select name="tenant_id" class="form-select" required>
                            <option value="">-- Pilih Tenant --</option>
                            @foreach($tenants as $t)
                                <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Pilih Paket SaaS</label>
                        <select name="saas_plan_id" class="form-select" required>
                            <option value="">-- Pilih Paket --</option>
                            @foreach($plans as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} - Rp {{ number_format($p->price, 0, ',', '.') }}/bln</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Durasi Langganan</label>
                        <select name="duration_months" class="form-select" required>
                            <option value="1">1 Bulan</option>
                            <option value="3">3 Bulan</option>
                            <option value="6">6 Bulan</option>
                            <option value="12" selected>12 Bulan (1 Tahun)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Aktifkan Langganan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
