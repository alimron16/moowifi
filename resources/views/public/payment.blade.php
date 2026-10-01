@extends('layouts.base')

@section('title', 'Pembayaran Tagihan - ' . $invoice->invoice_number)

@section('body-class', 'bg-body-tertiary')

@section('content')
<div class="page page-center py-4">
    <div class="container container-tight">
        <div class="text-center mb-4">
            <span class="d-inline-flex align-items-center gap-2 text-primary fs-2 fw-bold">
                <i class="ti ti-wifi fs-1"></i>
                <span>{{ $tenant->name }}</span>
            </span>
            <div class="text-secondary small mt-1">Layanan Internet & Tagihan Mandiri</div>
        </div>

        @if(session('success'))
            <x-alert type="success" class="mb-3">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="danger" class="mb-3">{{ session('error') }}</x-alert>
        @endif
        @if(session('info'))
            <x-alert type="info" class="mb-3">{{ session('info') }}</x-alert>
        @endif

        <!-- Card Tagihan -->
        <div class="card mb-3 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-secondary small">Nomor Tagihan</div>
                    <div class="fw-bold">{{ $invoice->invoice_number }}</div>
                </div>
                <div>
                    <x-badge :status="$invoice->status" :dot="true" />
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="text-secondary small">Pelanggan:</div>
                        <div class="fw-bold">{{ $invoice->customer->name }}</div>
                        <div class="text-secondary small">{{ $invoice->customer->customer_code }}</div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="text-secondary small">Jatuh Tempo:</div>
                        <div class="fw-bold {{ $invoice->due_date->isPast() && !$invoice->isPaid() ? 'text-danger' : '' }}">
                            {{ $invoice->due_date->format('d F Y') }}
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-body rounded border mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-secondary">Paket Layanan:</span>
                        <span class="fw-medium">{{ $invoice->customer->package->name ?? 'Internet Bulanan' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-secondary">Periode:</span>
                        <span>{{ $invoice->period_start->format('d/m/Y') }} - {{ $invoice->period_end->format('d/m/Y') }}</span>
                    </div>
                    @if($invoice->unique_code > 0)
                        <div class="d-flex justify-content-between mb-1 text-secondary small">
                            <span>Kode Unik Verifikasi:</span>
                            <span>+{{ $invoice->unique_code }}</span>
                        </div>
                    @endif
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total Pembayaran:</span>
                        <span class="h2 mb-0 text-primary fw-bold">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($invoice->isPaid())
                    <div class="alert alert-success d-flex align-items-center mb-0">
                        <i class="ti ti-circle-check fs-1 me-2 text-success"></i>
                        <div>
                            <div class="fw-bold">Tagihan Telah Lunas!</div>
                            <div class="small">Pembayaran telah diterima dan akses internet Anda aktif secara normal.</div>
                        </div>
                    </div>
                @elseif($pendingConfirmation)
                    <div class="alert alert-warning d-flex align-items-center mb-0">
                        <i class="ti ti-clock-pause fs-1 me-2 text-warning"></i>
                        <div>
                            <div class="fw-bold">Menunggu Verifikasi Pembayaran</div>
                            <div class="small">Bukti transfer telah dikirim dan sedang diperiksa oleh admin/keuangan. Akses internet akan segera dipulihkan setelah disetujui.</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if(!$invoice->isPaid() && !$pendingConfirmation)
            <!-- Pilihan Metode Pembayaran -->
            <div class="card shadow-sm" x-data="{ tab: 'manual' }">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                        <li class="nav-item">
                            <a href="#tab-manual" class="nav-link active" data-bs-toggle="tab" @click="tab = 'manual'">
                                <i class="ti ti-building-bank me-2"></i> Transfer Bank / E-Wallet
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tab-gateway" class="nav-link" data-bs-toggle="tab" @click="tab = 'gateway'">
                                <i class="ti ti-credit-card me-2"></i> Payment Gateway Otomatis
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Manual Transfer Tab -->
                        <div class="tab-pane active show" id="tab-manual">
                            @php
                                $manualMethods = $paymentMethods->where('type', 'MANUAL');
                            @endphp

                            @if($manualMethods->count() > 0)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih Rekening Tujuan Transfer:</label>
                                    @foreach($manualMethods as $m)
                                        <div class="p-3 border rounded mb-2 bg-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge bg-blue-lt mb-1">{{ $m->provider }}</span>
                                                    <div class="fw-bold fs-3">{{ $m->account_number }}</div>
                                                    <div class="text-secondary small">a.n. {{ $m->account_name }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <hr>

                                <form action="{{ route('payment.submit-manual', $invoice->payment_token) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <h4 class="card-title mb-3">Konfirmasi Bukti Transfer</h4>

                                    <div class="mb-3">
                                        <label class="form-label required">Rekening / E-Wallet Tujuan</label>
                                        <select name="payment_method_id" class="form-select" required>
                                            <option value="">Pilih rekening yang Anda transfer...</option>
                                            @foreach($manualMethods as $m)
                                                <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->account_number }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label required">Nama Pengirim</label>
                                            <input type="text" name="sender_name" class="form-control" placeholder="Nama di struk / akun" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label required">Bank / E-Wallet Pengirim</label>
                                            <input type="text" name="bank_name" class="form-control" placeholder="BCA / DANA / BRI" required>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label required">Nominal Ditransfer</label>
                                            <input type="number" name="transfer_amount" class="form-control" value="{{ (int)$invoice->total_amount }}" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label required">Tanggal Transfer</label>
                                            <input type="date" name="transfer_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required">Upload Foto Struk / Bukti Transfer</label>
                                        <input type="file" name="proof_image" class="form-control" accept="image/*" required>
                                        <small class="form-hint">Format JPG, PNG maksimal 5 MB.</small>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ti ti-upload me-1"></i> Kirim Konfirmasi Pembayaran
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-4 text-secondary">
                                    Metode transfer manual belum dikonfigurasi oleh pemilik RT/RW Net.
                                </div>
                            @endif
                        </div>

                        <!-- Gateway Tab -->
                        <div class="tab-pane" id="tab-gateway">
                            @php
                                $gatewayMethods = $paymentMethods->where('type', 'GATEWAY');
                            @endphp

                            @if($gatewayMethods->count() > 0)
                                @foreach($gatewayMethods as $gm)
                                    <form action="{{ route('payment.pay-gateway', $invoice->payment_token) }}" method="POST" class="mb-3">
                                        @csrf
                                        <input type="hidden" name="payment_method_id" value="{{ $gm->id }}">

                                        <div class="p-3 border rounded mb-3 bg-body text-center">
                                            <div class="fw-bold fs-3 mb-1">{{ $gm->name }}</div>
                                            <p class="text-secondary small mb-3">
                                                Mendukung pembayaran instan melalui QRIS, Virtual Account BCA, Mandiri, BRI, BNI, dan gerai minimarket.
                                            </p>
                                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                                <i class="ti ti-qrcode me-1"></i> Bayar Otomatis via {{ $gm->provider }}
                                            </button>
                                        </div>
                                    </form>
                                @endforeach
                            @else
                                <div class="text-center py-4 text-secondary">
                                    Payment gateway otomatis belum diaktifkan. Silakan gunakan metode Transfer Bank / E-Wallet di tab sebelah.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="text-center text-secondary mt-4 small">
            Butuh bantuan terkait pembayaran? Hubungi admin di <strong>{{ $tenant->phone ?? '-' }}</strong>
        </div>
    </div>
</div>
@endsection
