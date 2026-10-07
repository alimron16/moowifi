@extends('layouts.tenant')

@section('title', 'Pembayaran Langganan SaaS')

@section('tenant-content')
<div class="page-header d-print-none mb-3">
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
        {{-- Form Validation Errors --}}
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
                 KOLOM KIRI: RINGKASAN TAGIHAN & HUBUNGI ADMIN (STICKY)
            ============================================================ -->
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 1.5rem; z-index: 10;">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <span class="text-secondary fw-semibold small">Ringkasan Tagihan</span>
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
                    <div class="card-body p-4">
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

                        <div class="mb-3 pb-3 border-bottom">
                            <span class="text-secondary small d-block">Total Nominal Tagihan:</span>
                            <div class="display-6 fw-bold text-success">
                                Rp {{ number_format($subscription->amount, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Hubungi Admin Terintegrasi -->
                        <div class="pt-1">
                            <div class="text-secondary small mb-2 text-center">Butuh bantuan transaksi atau konfirmasi?</div>
                            @php
                                $adminPhone = '6288976291662';
                                $waText = urlencode("Halo Admin MooWiFi, saya pemilik RT/RW Net ({$tenant->name}) ingin konfirmasi pembayaran langganan paket {$subscription->saasPlan?->name} (No Order: {$subscription->order_number}) sebesar Rp " . number_format($subscription->amount, 0, ',', '.') . ".");
                            @endphp
                            <a href="https://wa.me/{{ $adminPhone }}?text={{ $waText }}" target="_blank" class="btn btn-outline-success w-100 py-2 d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="ti ti-brand-whatsapp fs-2"></i>
                                <span class="fw-semibold">Hubungi Admin</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                 KOLOM KANAN: PILIHAN METODE PEMBAYARAN (TABEL CLEAN)
            ============================================================ -->
            <div class="col-12 col-lg-8">

                {{-- Status Jika Sudah Menunggu Verifikasi Manual --}}
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
                                    Metode Transfer: <strong class="text-dark">{{ $subscription->payment_method }}</strong>
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

                {{-- Card Utama Nav-Tabs Pembayaran --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header border-bottom-0 pb-0 pt-3 px-3">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#tab-otomatis" class="nav-link active fw-bold py-2 px-3" data-bs-toggle="tab" aria-selected="true" role="tab">
                                    <i class="ti ti-credit-card text-primary me-2 fs-2"></i>
                                    <span>Bayar Otomatis</span>
                                    <span class="badge bg-success-lt ms-2 text-success">Instan</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#tab-manual" class="nav-link fw-bold py-2 px-3" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                                    <i class="ti ti-building-bank text-secondary me-2 fs-2"></i>
                                    <span>Transfer Bank Manual</span>
                                    <span class="badge bg-secondary-lt ms-2 text-dark">Verifikasi</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content">
                            <!-- ============================================================
                                 TAB 1: BAYAR OTOMATIS (QRIS & VIRTUAL ACCOUNT)
                            ============================================================ -->
                            <div class="tab-pane active show" id="tab-otomatis" role="tabpanel">
                                @if(!empty($onlinePaymentActive) && $subscription->status === 'PENDING')
                                    <div class="alert alert-info-lt mb-4 border-0">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ti ti-shield-check fs-2 text-info"></i>
                                            <div>
                                                <strong>Aktivasi Otomatis &amp; Instan</strong>
                                                <div class="small text-secondary">
                                                    Pembayaran diverifikasi secara real-time dalam hitungan detik tanpa perlu konfirmasi atau unggah struk manual.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <form action="{{ route('tenant.subscription.pay-duitku', $subscription->id) }}" method="POST">
                                        @csrf
                                        @if(($activeGateway ?? '') === 'DUITKU')
                                            <div class="mb-4">
                                                <label class="form-label fw-bold text-dark">Pilih Saluran Pembayaran:</label>
                                                <select name="payment_channel" class="form-select form-select-lg">
                                                    <option value="" selected>Semua Saluran (Pilih di Halaman Checkout: QRIS / Virtual Account)</option>
                                                    <optgroup label="QRIS (Semua E-Wallet &amp; m-Banking)">
                                                        <option value="NQ">QRIS (BCA, Mandiri, GoPay, OVO, ShopeePay, DANA)</option>
                                                        <option value="SP">QRIS ShopeePay</option>
                                                    </optgroup>
                                                    <optgroup label="Virtual Account Bank">
                                                        <option value="BC">BCA Virtual Account</option>
                                                        <option value="M2">Mandiri Virtual Account</option>
                                                        <option value="BR">BRI Virtual Account (BRIVA)</option>
                                                        <option value="I1">BNI Virtual Account</option>
                                                        <option value="BT">Permata Bank Virtual Account</option>
                                                        <option value="B1">CIMB Niaga Virtual Account</option>
                                                        <option value="BV">BSI Virtual Account</option>
                                                    </optgroup>
                                                    <optgroup label="E-Wallet">
                                                        <option value="DA">DANA</option>
                                                        <option value="OV">OVO</option>
                                                        <option value="SA">ShopeePay Apps</option>
                                                    </optgroup>
                                                    <optgroup label="Kartu Kredit">
                                                        <option value="VC">Kartu Kredit (Visa / MasterCard / JCB)</option>
                                                    </optgroup>
                                                </select>
                                                <small class="form-hint text-muted mt-1">
                                                    Pilih saluran spesifik untuk langsung membuka barcode / nomor VA, atau biarkan pilihan pertama untuk memilih di layar checkout.
                                                </small>
                                            </div>
                                        @else
                                            <div class="p-3 border rounded-3 mb-4 bg-light text-center">
                                                <div class="text-secondary small mb-1">Metode Pembayaran Tersedia:</div>
                                                <div class="fw-bold text-dark fs-3">QRIS, Virtual Account Semua Bank, &amp; E-Wallet</div>
                                            </div>
                                        @endif

                                        <button type="submit" class="btn btn-primary w-100 py-3 fs-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                            <i class="ti ti-credit-card fs-2"></i>
                                            <span>Bayar (Rp {{ number_format($subscription->amount, 0, ',', '.') }})</span>
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center py-4 text-muted">
                                        <i class="ti ti-info-circle fs-1 mb-2 d-block text-secondary"></i>
                                        <p class="mb-0">
                                            @if($subscription->status !== 'PENDING')
                                                Tagihan ini tidak lagi dalam status menunggu pembayaran.
                                            @else
                                                Metode pembayaran otomatis sedang disiapkan oleh Admin. Silakan gunakan metode <strong>Transfer Bank Manual</strong> pada tab di samping.
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- ============================================================
                                 TAB 2: TRANSFER BANK MANUAL
                            ============================================================ -->
                            <div class="tab-pane" id="tab-manual" role="tabpanel">
                                <!-- Bagian Rekening Resmi -->
                                <div class="mb-4">
                                    <label class="form-label text-secondary fw-semibold mb-2">
                                        1. Silakan transfer tepat sebesar <strong class="text-success">Rp {{ number_format($subscription->amount, 0, ',', '.') }}</strong> ke salah satu <strong>Rekening Resmi Pembayaran Platform</strong> berikut:
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
                                                    Rekening Resmi Pembayaran Platform belum dikonfigurasi oleh Admin.
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="hr my-4"></div>

                                <!-- Bagian Form Unggah Bukti -->
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
