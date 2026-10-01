@extends('layouts.tenant')

@section('title', 'Tagihan ' . $invoice->invoice_number)

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Rincian Invoice</div>
                <h2 class="page-title">{{ $invoice->invoice_number }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('tenant.invoices.pdf', $invoice->id) }}" class="btn btn-outline-secondary">
                        <i class="ti ti-file-download me-1"></i> Unduh PDF
                    </a>
                    <form action="{{ route('tenant.invoices.send-email', $invoice->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-info" title="Kirim Invoice PDF via Akun Gmail Pemilik">
                            <i class="ti ti-mail me-1"></i> Kirim Email (Gmail)
                        </button>
                    </form>
                    <form action="{{ route('tenant.invoices.send-whatsapp', $invoice->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success" title="Kirim Notifikasi Tagihan via WhatsApp Fonnte">
                            <i class="ti ti-brand-whatsapp me-1"></i> Kirim WA
                        </button>
                    </form>
                    <a href="{{ $invoice->payment_url }}" target="_blank" class="btn btn-outline-primary">
                        <i class="ti ti-external-link me-1"></i> Buka Link Bayar
                    </a>
                    @if(!$invoice->isPaid())
                        <form action="{{ route('tenant.invoices.mark-paid', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tandai tagihan ini telah dibayar lunas?')">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check me-1"></i> Tandai Lunas (Kasir)
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card card-lg">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="ti ti-wifi fs-1 text-primary"></i>
                            <h2 class="h1 mb-0">{{ $tenant->name }}</h2>
                        </div>
                        <p class="text-secondary mb-0">{{ $tenant->address ?? 'Alamat operasional RT/RW Net' }}</p>
                        <p class="text-secondary">Telp / WA: {{ $tenant->phone ?? '-' }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <p class="h1 mb-1">{{ $invoice->invoice_number }}</p>
                        <div class="mb-2">
                            <x-badge :status="$invoice->status" :dot="true" />
                        </div>
                        <div class="text-secondary small">
                            Tanggal Terbit: <strong>{{ $invoice->issue_date->format('d/m/Y') }}</strong><br>
                            Jatuh Tempo: <strong class="{{ $invoice->due_date->isPast() && !$invoice->isPaid() ? 'text-danger' : '' }}">{{ $invoice->due_date->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row mb-4">
                    <div class="col-6">
                        <div class="text-secondary text-uppercase fw-bold small">Ditagihkan Kepada:</div>
                        <h3 class="h3 mb-1 mt-2">{{ $invoice->customer->name }}</h3>
                        <div class="text-secondary">
                            Kode Pelanggan: <strong>{{ $invoice->customer->customer_code }}</strong><br>
                            Telepon: {{ $invoice->customer->phone }}<br>
                            Alamat: {{ $invoice->customer->address ?? '-' }}
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="text-secondary text-uppercase fw-bold small">Periode Layanan:</div>
                        <div class="mt-2 text-secondary">
                            {{ $invoice->period_start->format('d F Y') }}<br>
                            sampai dengan<br>
                            {{ $invoice->period_end->format('d F Y') }}
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-transparent table-responsive">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 1%">#</th>
                                <th>Rincian Item</th>
                                <th class="text-center" style="width: 1%">Kuantitas</th>
                                <th class="text-end" style="width: 1%">Harga</th>
                                <th class="text-end" style="width: 1%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <p class="strong mb-1">{{ $item->description }}</p>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="strong text-end">Subtotal</td>
                                <td class="text-end">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @if($invoice->discount_amount > 0)
                                <tr>
                                    <td colspan="4" class="strong text-end text-danger">Potongan Diskon</td>
                                    <td class="text-end text-danger">- Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="4" class="font-weight-bold text-uppercase text-end">Total Pembayaran</td>
                                <td class="font-weight-bold text-end h2 text-primary">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 p-3 bg-body-tertiary rounded d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold">Payment Link Publik:</div>
                        <a href="{{ $invoice->payment_url }}" target="_blank" class="small text-break">
                            {{ $invoice->payment_url }}
                        </a>
                    </div>
                    <div>
                        <a href="https://api.whatsapp.com/send?phone={{ preg_replace('/[^0-9]/', '', $invoice->customer->phone) }}&text={{ urlencode('Halo ' . $invoice->customer->name . ', ini tagihan internet Anda nomor ' . $invoice->invoice_number . ' sebesar Rp ' . number_format($invoice->total_amount, 0, ',', '.') . '. Silakan bayar melalui link: ' . $invoice->payment_url) }}" target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="ti ti-brand-whatsapp me-1"></i> Kirim WhatsApp Manual
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
