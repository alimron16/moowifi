@extends('layouts.tenant')

@section('title', 'Pembayaran Langganan SaaS')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">SaaS Invoice &amp; Billing</div>
                <h2 class="page-title">Pembayaran Tagihan Langganan Paket</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.subscription.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Pilihan Paket
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="ti ti-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="ti ti-alert-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="ti ti-alert-circle me-2"></i> <strong>Mohon periksa formulir konfirmasi:</strong>
                <ul class="mb-0 mt-1 small">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3">
            <!-- ============================================================
                 KOLOM KIRI: RINGKASAN TAGIHAN & BANTUAN (STICKY)
            ============================================================ -->
            <div class="col-12 col-lg-4">
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="text-secondary small fw-medium">Ringkasan Tagihan</span>
                        <div>
                            @if($subscription->status === 'WAITING_VERIFICATION')
                                <span class="badge bg-yellow text-dark px-2 py-1">
                                    <i class="ti ti-clock me-1"></i> Menunggu Verifikasi
                                </span>
                            @elseif($subscription->status === 'ACTIVE')
                                <span class="badge bg-green text-white px-2 py-1">
                                    <i class="ti ti-check me-1"></i> Lunas &amp; Aktif
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-2 py-1">
                                    <i class="ti ti-wallet me-1"></i> Menunggu Pembayaran
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 pb-3 border-bottom">
                            <span class="text-secondary small d-block">Nomor Pesanan:</span>
                            <div class="fw-bold font-monospace text-dark fs-3">
                                {{ $subscription->order_number ?? ('ORD-SUB-' . $subscription->id) }}
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <span class="text-secondary small d-block">Paket yang Dipilih:</span>
                            <h3 class="text-primary mb-1 fw-bold">Paket {{ $subscription->saasPlan?->name ?? 'Pro' }}</h3>
                            <div class="text-secondary small">
                                Periode: <strong>{{ $subscription->billing_cycle === 'yearly' ? 'Tahunan (12 Bulan - Hemat 20%)' : 'Bulanan (1 Bulan)' }}</strong>
                            </div>
                            <div class="text-muted small mt-1">
                                Kuota: {{ number_format($subscription->saasPlan?->max_customers ?? 0) }} Pelanggan &bull; {{ $subscription->saasPlan?->max_routers ?? 1 }} Router
                            </div>
                        </div>

                        <div>
                            <span class="text-secondary small d-block">Total Nominal:</span>
                            <div class="display-6 fw-bold text-success">
                                Rp {{ number_format($subscription->amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Butuh Bantuan / Kontak Admin -->
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center p-3">
                        <div class="text-secondary small mb-2">Butuh bantuan transaksi atau konfirmasi?</div>
                        @php
                            $adminPhone = preg_replace('/[^0-9]/', '', $platformProfile['contact_phone'] ?? '088976291662');
                            if (str_starts_with($adminPhone, '0')) {
                                $adminPhone = '62' . substr($adminPhone, 1);
                            }
                            $waText = urlencode("Halo Admin MooWiFi, saya pemilik RT/RW Net ({$tenant->name}) ingin konfirmasi pembayaran langganan paket {$subscription->saasPlan?->name} (No Order: {$subscription->order_number}) sebesar Rp " . number_format($subscription->amount, 0, ',', '.') . ".");
                        @endphp
                        <a href="https://wa.me/{{ $adminPhone }}?text={{ $waText }}" target="_blank" class="btn btn-outline-success w-100 d-inline-flex align-items-center justify-content-center gap-2">
                            <i class="ti ti-brand-whatsapp fs-3"></i>
                            <span>Hubungi Admin</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                 KOLOM KANAN: PILIHAN METODE PEMBAYARAN
            ============================================================ -->
            <div class="col-12 col-lg-8">

                {{-- Status Jika Sudah Menunggu Verifikasi --}}
                @if($subscription->status === 'WAITING_VERIFICATION')
                    <div class="card border-warning mb-3 shadow-sm bg-warning-lt">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <i class="ti ti-clock fs-1 text-warning"></i>
                                <div>
                                    <h3 class="h3 fw-bold text-dark mb-0">Bukti Pembayaran Sedang Diverifikasi Admin</h3>
                                    <div class="text-secondary small">Estimasi waktu verifikasi 5 - 15 menit pada jam operasional.</div>
                                </div>
                            </div>
                            <div class="hr my-3"></div>
                            <div class="row align-items-center g-2">
                                <div class="col-md-6 text-secondary small">
                                    Metode Pembayaran: <strong class="text-dark">{{ $subscription->payment_method }}</strong>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    @if($subscription->proof_path)
                                        <a href="{{ asset('storage/' . $subscription->proof_path) }}" target="_blank" class="btn btn-sm btn-white">
                                            <i class="ti ti-file-text me-1"></i> Lihat File Bukti Transfer
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- OPSI 1: PEMBAYARAN INSTAN VIA DUITKU (JIKA AKTIF & BELUM LUNAS) --}}
                @if(!empty($duitkuActive) && $subscription->status === 'PENDING')
                    <div class="card mb-3 border-primary shadow-sm" style="border-width: 2px;">
                        <div class="card-header bg-primary-lt d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary text-white p-2 rounded">
                                    <i class="ti ti-bolt fs-3"></i>
                                </span>
                                <div>
                                    <h3 class="card-title text-primary fw-bold mb-0">Metode 1: Bayar Otomatis via Duitku</h3>
                                    <div class="text-muted small">QRIS (Semua E-Wallet &amp; m-Banking) &bull; Virtual Account &bull; Aktivasi Otomatis Instan</div>
                                </div>
                            </div>
                            <span class="badge bg-success text-white px-2 py-1">Otomatis Aktif</span>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-secondary mb-3">
                                Bayar instan menggunakan <strong>QRIS</strong> atau <strong>Virtual Account Bank</strong>. Sistem akan langsung memverifikasi transaksi dan mengaktifkan paket Anda secara otomatis dalam beberapa detik tanpa perlu unggah bukti transfer.
                            </p>
                            <form action="{{ route('tenant.subscription.pay-duitku', $subscription->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100 py-3 fs-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                    <i class="ti ti-qrcode fs-2"></i>
                                    <span>Bayar Sekarang via Duitku (Rp {{ number_format($subscription->amount, 0, ',', '.') }})</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="text-center my-3 text-muted position-relative">
                        <span class="bg-white px-3 fw-semibold small text-secondary position-relative" style="z-index: 2;">
                            ATAU TRANSFER BANK MANUAL
                        </span>
                        <div class="position-absolute top-50 start-0 end-0 border-top" style="z-index: 1;"></div>
                    </div>
                @endif

                {{-- OPSI 2: TRANSFER BANK MANUAL & FORM UPLOAD BUKTI (TERPADU DALAM 1 CARD) --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <h3 class="card-title text-dark fw-bold mb-0">
                            <i class="ti ti-building-bank me-2 text-primary"></i> 
                            @if(!empty($duitkuActive))
                                Metode 2: Transfer Bank Manual (Rekening Resmi Pembayaran Platform)
                            @else
                                Rekening Resmi Pembayaran Platform
                            @endif
                        </h3>
                        <span class="badge bg-secondary-lt text-dark">Verifikasi Manual</span>
                    </div>

                    <div class="card-body p-4">
                        <!-- Daftar Rekening Bank Platform -->
                        <div class="mb-4">
                            <label class="form-label text-secondary fw-semibold mb-2">
                                1. Silakan transfer tepat sebesar <strong class="text-success">Rp {{ number_format($subscription->amount, 0, ',', '.') }}</strong> ke salah satu rekening resmi berikut:
                            </label>
                            
                            <div class="row g-2">
                                @forelse($manualBanks as $index => $bank)
                                    <div class="col-12 col-md-6">
                                        <div class="p-3 border rounded-3 bg-light h-100">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="badge bg-primary text-white fw-bold">{{ $bank['bank_name'] ?? 'BANK' }}</span>
                                                <button type="button" class="btn btn-sm btn-white py-1 px-2" onclick="copyAccNumber('accNumber-{{ $index }}')">
                                                    <i class="ti ti-copy me-1"></i> Salin
                                                </button>
                                            </div>
                                            <div class="font-monospace fs-2 fw-bold text-dark mt-1" id="accNumber-{{ $index }}">
                                                {{ $bank['account_number'] ?? '-' }}
                                            </div>
                                            <div class="small text-secondary mt-1">
                                                Atas Nama: <strong class="text-dark">{{ $bank['account_name'] ?? '-' }}</strong>
                                            </div>
                                            @if(!empty($bank['instructions']))
                                                <div class="small text-muted mt-2 border-top pt-2 fst-italic">
                                                    {{ $bank['instructions'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="p-3 text-center text-muted border rounded">
                                            Rekening pembayaran manual belum diatur oleh Admin.
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="hr my-4"></div>

                        <!-- Form Unggah Bukti Transfer -->
                        @if($subscription->status !== 'WAITING_VERIFICATION' && $subscription->status !== 'ACTIVE')
                            <div>
                                <label class="form-label text-secondary fw-semibold mb-3">
                                    2. Setelah melakukan transfer, unggah foto bukti transfer di bawah ini:
                                </label>

                                <form action="{{ route('tenant.subscription.confirm-payment', $subscription->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label required">Bank Tujuan yang Ditransfer</label>
                                            <select name="payment_method" class="form-select" required>
                                                <option value="" disabled selected>-- Pilih Bank Tujuan --</option>
                                                @foreach($manualBanks as $b)
                                                    <option value="{{ $b['bank_name'] }} - {{ $b['account_number'] }}">
                                                        {{ $b['bank_name'] }} ({{ $b['account_number'] }} a.n {{ $b['account_name'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label class="form-label required">Unggah File Bukti / Struk</label>
                                            <input type="file" name="proof" class="form-control" accept="image/*,.pdf" required>
                                            <small class="form-hint">Format file: JPG, PNG, atau PDF (Maks. 3 MB).</small>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-success w-100 py-2 fs-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                                                <i class="ti ti-send fs-2"></i>
                                                <span>Kirim Bukti Pembayaran Manual</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="text-center py-2 text-secondary small">
                                <i class="ti ti-check text-success me-1"></i> Bukti pembayaran telah tercatat. Hubungi admin jika butuh verifikasi mendesak.
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function copyAccNumber(elementId) {
    const text = document.getElementById(elementId).innerText.trim();
    navigator.clipboard.writeText(text);
    alert('Nomor rekening ' + text + ' berhasil disalin!');
}
</script>
@endsection
