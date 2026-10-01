@extends('layouts.tenant')

@section('title', 'Log Transaksi Payment Gateway')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Audit & Gateway</div>
                <h2 class="page-title">Log Transaksi Payment Gateway</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.payment-methods.gateway') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-adjustments me-1"></i> Pengaturan Gateway
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
                            <th>Waktu</th>
                            <th>Provider Gateway</th>
                            <th>No. Referensi / Trx ID</th>
                            <th>Tagihan & Pelanggan</th>
                            <th>Nominal</th>
                            <th>Biaya Fee</th>
                            <th>Metode / Channel</th>
                            <th>Status Gateway</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $t)
                            <tr>
                                <td class="text-secondary small font-monospace">
                                    {{ $t->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td>
                                    <span class="badge bg-purple-lt fw-bold">{{ $t->provider }}</span>
                                </td>
                                <td>
                                    <div class="font-monospace small fw-bold">{{ $t->provider_reference ?? '-' }}</div>
                                    <div class="text-secondary small">Pay ID: #{{ $t->payment_id }}</div>
                                </td>
                                <td>
                                    @if($t->payment?->invoice)
                                        <div class="fw-bold">{{ $t->payment->invoice->invoice_number }}</div>
                                        <div class="text-secondary small">{{ $t->payment->customer->name ?? '-' }}</div>
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif
                                </td>
                                <td class="fw-bold">
                                    Rp {{ number_format($t->amount, 0, ',', '.') }}
                                </td>
                                <td class="text-secondary small">
                                    Rp {{ number_format($t->fee, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge bg-azure-lt">{{ $t->payment_channel ?? 'ONLINE' }}</span>
                                </td>
                                <td>
                                    @if(in_array($t->status, ['SUCCESS', 'PAID', 'SETTLEMENT']))
                                        <span class="badge bg-success-lt">SUCCESS</span>
                                    @elseif(in_array($t->status, ['PENDING', 'UNPAID']))
                                        <span class="badge bg-warning-lt">{{ $t->status }}</span>
                                    @else
                                        <span class="badge bg-danger-lt">{{ $t->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <x-empty-state 
                                        title="Belum Ada Log Transaksi" 
                                        subtitle="Setiap pembayaran melalui gateway Duitku, Midtrans, Xendit, atau Tripay akan tercatat di sini secara transparan."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="card-footer d-flex align-items-center">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
