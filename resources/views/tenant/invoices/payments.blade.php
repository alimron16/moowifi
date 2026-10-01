@extends('layouts.tenant')

@section('title', 'Riwayat Penerimaan Pembayaran')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Keuangan & Kas</div>
                <h2 class="page-title">Riwayat Penerimaan Pembayaran Tagihan</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.invoices.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-receipt me-1"></i> Lihat Tagihan
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
                            <th>Kode Bayar</th>
                            <th>No. Tagihan</th>
                            <th>Pelanggan</th>
                            <th>Metode Pembayaran</th>
                            <th>Jumlah Pelunasan</th>
                            <th>Waktu Pelunasan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $p)
                            <tr>
                                <td class="font-monospace small fw-bold">
                                    {{ $p->payment_code }}
                                </td>
                                <td>
                                    @if($p->invoice)
                                        <a href="{{ route('tenant.invoices.show', $p->invoice->id) }}" class="fw-bold">
                                            {{ $p->invoice->invoice_number }}
                                        </a>
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $p->customer->name ?? '-' }}</div>
                                    <div class="text-secondary small">{{ $p->customer->customer_code ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-blue-lt">
                                        {{ $p->paymentMethod->name ?? ($p->paymentMethod->provider ?? 'Gateway / Kasir') }}
                                    </span>
                                </td>
                                <td class="fw-bold text-success">
                                    {{ $p->formatted_amount }}
                                </td>
                                <td class="text-secondary small font-monospace">
                                    {{ $p->paid_at ? $p->paid_at->format('d/m/Y H:i') : ($p->created_at->format('d/m/Y H:i')) }}
                                </td>
                                <td>
                                    <x-badge :status="$p->status" :dot="true" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-empty-state 
                                        title="Belum Ada Pembayaran" 
                                        subtitle="Semua pembayaran yang lunas melalui payment gateway atau verifikasi kasir akan tampil di sini."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
                <div class="card-footer d-flex align-items-center">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
