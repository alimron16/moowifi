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
                                @php
                                    $uniqueCode = $invoice->unique_code > 0 ? $invoice->unique_code : 0;
                                    $manualAmount = $invoice->manual_amount;
                                @endphp

                                <!-- Banner Nominal Transfer Manual Termasuk 3 Digit Kode Unik -->
                                <div class="alert alert-warning-lt border-warning mb-4">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="text-secondary small fw-medium">Total Nominal Transfer Manual (Termasuk 3 Digit Kode Unik):</div>
                                            <div class="display-6 fw-bold text-danger mt-1">
                                                Rp {{ number_format($manualAmount, 0, ',', '.') }}
                                            </div>
                                            <div class="small text-muted mt-1">
                                                Harga Tagihan: Rp {{ number_format($invoice->total_amount, 0, ',', '.') }} &bull; 
                                                Kode Unik Verifikasi: <strong class="badge bg-danger text-white px-2 py-1">+{{ $uniqueCode }}</strong>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="navigator.clipboard.writeText('{{ (int)$manualAmount }}'); alert('Nominal transfer Rp {{ number_format($manualAmount, 0, ',', '.') }} berhasil disalin!');">
                                                <i class="ti ti-copy me-1"></i> Salin Nominal Tepat
                                            </button>
                                        </div>
                                    </div>
                                    <div class="small text-danger fw-semibold mt-2 pt-2 border-top border-warning-subtle">
                                        <i class="ti ti-alert-triangle me-1"></i> <strong>PENTING:</strong> Mohon transfer <strong>TEPAT</strong> hingga 3 digit terakhir (<span class="font-monospace fw-bold">Rp {{ number_format($manualAmount, 0, ',', '.') }}</span>) agar mutasi dapat diverifikasi cepat oleh admin RT/RW Net.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilihan Rekening &amp; QRIS Pembayaran:</label>
                                    @foreach($manualMethods as $m)
                                        @if($m->provider === 'QRIS' || $m->qr_code_image)
                                            <div class="p-3 border border-purple rounded-3 mb-3 bg-purple-lt shadow-sm">
                                                <div class="row align-items-center g-3">
                                                    @if($m->qr_code_image)
                                                        <div class="col-12 col-sm-auto text-center">
                                                            <div class="bg-white p-2 rounded-2 border d-inline-block shadow-xs">
                                                                <img src="{{ asset('storage/' . $m->qr_code_image) }}" alt="QRIS {{ $m->name }}" class="img-fluid" style="max-width: 180px; max-height: 180px; object-fit: contain;">
                                                            </div>
                                                            <div class="mt-2">
                                                                <a href="{{ asset('storage/' . $m->qr_code_image) }}" download="QRIS-{{ $invoice->invoice_number }}.png" class="btn btn-sm btn-white text-purple">
                                                                    <i class="ti ti-download me-1"></i> Simpan Gambar QRIS
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col">
                                                        <span class="badge bg-purple text-white fw-bold mb-1">
                                                            <i class="ti ti-qrcode me-1"></i> QRIS Pembayaran
                                                        </span>
                                                        <div class="fw-bold fs-3 text-dark">{{ $m->name }}</div>
                                                        @if($m->account_number && $m->account_number !== 'QRIS Statis')
                                                            <div class="text-secondary small mt-1">NMID / ID Merchant: <span class="font-monospace fw-bold text-dark">{{ $m->account_number }}</span></div>
                                                        @endif
                                                        @if($m->account_name)
                                                            <div class="text-secondary small">Merchant: <strong class="text-dark">{{ $m->account_name }}</strong></div>
                                                        @endif
                                                        <div class="alert alert-info-lt py-2 px-3 small mt-2 mb-0">
                                                            <i class="ti ti-scan me-1"></i> Buka aplikasi mobile banking (BCA, Mandiri, BRI, BNI) atau E-Wallet (GoPay, OVO, DANA, ShopeePay), lalu <strong>Scan QRIS</strong> di samping.
                                                        </div>
                                                        @if(!empty($m->instructions))
                                                            <div class="text-secondary small mt-2 fst-italic">{{ $m->instructions }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="p-3 border rounded mb-2 bg-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="badge bg-blue-lt mb-1">
                                                            <i class="ti ti-building-bank me-1"></i> {{ $m->provider }}
                                                        </span>
                                                        <div class="fw-bold fs-3 font-monospace text-dark" id="copy-bank-{{ $m->id }}">{{ $m->account_number }}</div>
                                                        <div class="text-secondary small">a.n. <strong class="text-dark">{{ $m->account_name }}</strong> ({{ $m->name }})</div>
                                                        @if(!empty($m->instructions))
                                                            <div class="text-muted small mt-1 fst-italic">{{ $m->instructions }}</div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText(document.getElementById('copy-bank-{{ $m->id }}').innerText.trim()); alert('Nomor rekening disalin!');">
                                                            <i class="ti ti-copy me-1"></i> Salin No. Rek
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <hr>

                                <form action="{{ route('payment.submit-manual', $invoice->payment_token) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <h4 class="card-title mb-3">Konfirmasi Bukti Transfer</h4>

                                    <div class="mb-3">
                                        <label class="form-label required">Rekening / Kanal Tujuan</label>
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
                                            <input type="number" name="transfer_amount" class="form-control" value="{{ (int)$manualAmount }}" required>
                                            <small class="form-hint text-danger">Termasuk kode unik: Rp {{ number_format($manualAmount, 0, ',', '.') }}</small>
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

                                        <div class="p-4 border rounded-3 mb-3 bg-body text-center shadow-xs">
                                            <div class="badge bg-success-lt text-success fw-bold px-3 py-1 mb-2">
                                                <i class="ti ti-bolt me-1"></i> Verifikasi Otomatis Instan
                                            </div>
                                            <div class="fw-bold fs-2 mb-1">Pembayaran Otomatis</div>
                                            <p class="text-secondary small mb-3">
                                                Mendukung QRIS (Semua Bank &amp; E-Wallet), Virtual Account BCA, Mandiri, BRI, BNI, Permata, serta gerai minimarket.
                                            </p>

                                            @if($gm->provider === 'DUITKU')
                                                <div class="mb-3 text-start">
                                                    <label class="form-label small text-secondary">Pilih Saluran Pembayaran (Opsional):</label>
                                                    <select name="payment_channel" class="form-select">
                                                        <option value="" selected>Semua Saluran (Pilih di Halaman Checkout)</option>
                                                        <optgroup label="QRIS">
                                                            <option value="NQ">QRIS (Semua Bank &amp; E-Wallet)</option>
                                                        </optgroup>
                                                        <optgroup label="Virtual Account">
                                                            <option value="BC">BCA Virtual Account</option>
                                                            <option value="M2">Mandiri Virtual Account</option>
                                                            <option value="BR">BRI Virtual Account (BRIVA)</option>
                                                            <option value="I1">BNI Virtual Account</option>
                                                            <option value="BT">Permata Virtual Account</option>
                                                            <option value="B1">CIMB Niaga Virtual Account</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            @endif

                                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold fs-3 shadow-sm">
                                                <i class="ti ti-credit-card me-2"></i> Bayar Sekarang (Rp {{ number_format($invoice->total_amount, 0, ',', '.') }})
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
