@extends('layouts.tenant')

@section('title', 'Daftar Tagihan')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Manajemen Billing</div>
                <h2 class="page-title">Daftar Tagihan Pelanggan</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.invoices.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Terbitkan Tagihan Baru
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('tenant.invoices.index') }}" class="row g-2">
                    <div class="col-md-5">
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control" placeholder="Cari nomor tagihan atau nama pelanggan..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="UNPAID" {{ request('status') === 'UNPAID' ? 'selected' : '' }}>Belum Lunas (Unpaid)</option>
                            <option value="PAID" {{ request('status') === 'PAID' ? 'selected' : '' }}>Lunas (Paid)</option>
                            <option value="OVERDUE" {{ request('status') === 'OVERDUE' ? 'selected' : '' }}>Jatuh Tempo (Overdue)</option>
                            <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="month" name="month" class="form-control" value="{{ request('month') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary w-100">Saring</button>
                        <a href="{{ route('tenant.invoices.index') }}" class="btn btn-ghost-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Nomor Tagihan</th>
                            <th>Pelanggan</th>
                            <th>Periode</th>
                            <th>Jatuh Tempo</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                            <tr>
                                <td>
                                    <a href="{{ route('tenant.invoices.show', $inv->id) }}" class="fw-bold text-decoration-none">
                                        {{ $inv->invoice_number }}
                                    </a>
                                    <div class="text-secondary small">{{ $inv->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $inv->customer->name ?? '-' }}</div>
                                    <div class="text-secondary small">{{ $inv->customer->customer_code ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="text-secondary small">
                                        {{ $inv->period_start->format('d/m/Y') }} s/d {{ $inv->period_end->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="{{ $inv->due_date->isPast() && !$inv->isPaid() ? 'text-danger fw-bold' : '' }}">
                                        {{ $inv->due_date->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="fw-bold">
                                    Rp {{ number_format($inv->total_amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <x-badge :status="$inv->status" />
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('tenant.invoices.show', $inv->id) }}" class="btn btn-sm btn-ghost-primary">
                                            Rincian
                                        </a>
                                        <a href="{{ $inv->payment_url }}" target="_blank" class="btn btn-sm btn-ghost-secondary" title="Buka Halaman Pembayaran Pelanggan">
                                            <i class="ti ti-external-link"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-empty-state 
                                        title="Belum Ada Tagihan" 
                                        subtitle="Terbitkan tagihan baru untuk pelanggan internet Anda."
                                    >
                                        <x-slot:action>
                                            <a href="{{ route('tenant.invoices.create') }}" class="btn btn-primary">
                                                <i class="ti ti-plus me-1"></i> Buat Tagihan
                                            </a>
                                        </x-slot:action>
                                    </x-empty-state>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($invoices->hasPages())
                <div class="card-footer d-flex align-items-center">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
