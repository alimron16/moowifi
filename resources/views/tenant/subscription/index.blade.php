@extends('layouts.tenant')

@section('title', 'Paket & Langganan SaaS')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Paket Layanan & Kapasitas Jaringan</div>
                <h2 class="page-title">Langganan & Upgrade Paket MooWiFi</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Dasbor
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        @if($isExpired)
            <div class="alert alert-danger mb-4 p-3 border-danger shadow-sm" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar bg-danger text-white rounded">
                        <i class="ti ti-alert-octagon fs-2"></i>
                    </span>
                    <div>
                        <h4 class="alert-title text-danger mb-1">Masa Uji Coba Gratis / Langganan Anda Telah Berakhir!</h4>
                        <div class="text-secondary small">
                            Akses otomatisasi penagihan dan penambahan pelanggan/router dibatasi sementara. Silakan pilih dan aktifkan paket berlangganan di bawah ini untuk melanjutkan operasional RT/RW Net Anda tanpa hambatan.
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($pendingOrder)
            <div class="card mb-4 border-warning-subtle bg-warning-lt shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar bg-warning text-dark rounded">
                                <i class="ti ti-receipt-2 fs-2"></i>
                            </span>
                            <div>
                                <h4 class="mb-1 text-dark">
                                    Pesanan Langganan {{ $pendingOrder->saasPlan?->name ?? 'Paket' }} Menunggu {{ $pendingOrder->status === 'WAITING_VERIFICATION' ? 'Verifikasi Admin' : 'Pembayaran Transfer' }}
                                </h4>
                                <div class="text-secondary small">
                                    Nomor Tagihan: <strong class="font-monospace text-dark">{{ $pendingOrder->order_number }}</strong> &bull; Total: <strong class="text-dark">Rp {{ number_format($pendingOrder->amount, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('tenant.subscription.payment', $pendingOrder->id) }}" class="btn btn-warning text-dark">
                                <i class="ti ti-wallet me-1"></i> Buka Instruksi Pembayaran Transfer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Card Status Langganan Saat Ini -->
        <div class="card mb-4 border-primary-subtle bg-primary-lt">
            <div class="card-body p-3 p-md-4">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-md-4">
                        <div class="text-secondary small text-uppercase fw-bold mb-1">Paket Berlangganan Saat Ini</div>
                        <h2 class="text-primary mb-1">
                            <i class="ti ti-crown me-1"></i> Paket {{ $activePlan?->name ?? 'Standar' }}
                        </h2>
                        <div>
                            @if($isExpired)
                                <span class="badge bg-danger text-white px-2 py-1">
                                    <i class="ti ti-clock-off me-1"></i> Kedaluwarsa
                                </span>
                            @elseif($isTrial)
                                <span class="badge bg-yellow text-dark px-2 py-1">
                                    <i class="ti ti-clock me-1"></i> Uji Coba Gratis (Sisa {{ $trialDaysLeft }} Hari)
                                </span>
                            @else
                                <span class="badge bg-green text-white px-2 py-1">
                                    <i class="ti ti-check me-1"></i> Aktif (Sisa {{ $subscriptionDaysLeft }} Hari)
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6 col-md-4 border-start-md">
                        <div class="text-secondary small mb-1">Kapasitas Maksimal Paket:</div>
                        <div class="fw-bold text-dark fs-3 mb-0">{{ number_format($tenant->getMaxCustomers()) }} Pelanggan</div>
                        <div class="small text-secondary">{{ $tenant->getMaxRouters() }} Router MikroTik &bull; {{ $tenant->getMaxWhatsappGateways() }} WhatsApp Device</div>
                    </div>
                    <div class="col-6 col-md-4 border-start-md">
                        <div class="text-secondary small mb-1">Masa Berlaku Hingga:</div>
                        <div class="fw-bold text-dark fs-4">
                            {{ $currentSubscription?->ends_at ? $currentSubscription->ends_at->translatedFormat('d F Y') : ($tenant->trial_ends_at ? $tenant->trial_ends_at->translatedFormat('d F Y') : '-') }}
                        </div>
                        <div class="small text-muted">Server RADIUS Cloud & Auto-Cut Aktif</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Pilihan Paket -->
        <div class="text-center mb-4">
            <h3 class="mb-1 text-dark">Pilihan Paket Pertumbuhan Bisnis RT/RW Net</h3>
            <p class="text-secondary small">
                Tingkatkan kapasitas pelanggan dan akses router kapan saja. Biaya dihitung secara proporsional.
            </p>
        </div>

        <div class="row row-cards justify-content-center">
            @foreach($plans as $plan)
                @php
                    $isCurrent = $activePlan && $activePlan->id === $plan->id;
                    $isUpgrade = $activePlan ? ((float)$plan->price > $currentPrice) : true;
                    $isDowngrade = $activePlan ? ((float)$plan->price < $currentPrice) : false;
                @endphp
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100 {{ $isCurrent ? 'border-primary shadow-sm' : '' }}">
                        @if($isCurrent)
                            <div class="ribbon bg-primary text-white">Aktif</div>
                        @endif
                        <div class="card-body p-3 p-xl-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h3 class="card-title text-dark mb-0">{{ $plan->name }}</h3>
                                @if(str_contains(strtoupper($plan->name), 'PRO'))
                                    <span class="badge bg-purple-lt">Populer</span>
                                @endif
                            </div>
                            <div class="text-secondary small mb-3">{{ $plan->description ?? 'Solusi manajemen jaringan terpadu.' }}</div>

                            <div class="my-2">
                                <div class="text-secondary small">Mulai dari</div>
                                <div class="d-flex align-items-baseline">
                                    <span class="fs-4 fw-bold text-dark">Rp</span>
                                    <span class="display-6 fw-bold text-dark mx-1">{{ number_format($plan->price, 0, ',', '.') }}</span>
                                    <span class="text-muted small">/bulan</span>
                                </div>
                            </div>

                            <div class="hr my-3"></div>

                            <ul class="list-unstyled space-y-2 mb-4 small flex-grow-1">
                                <li class="d-flex align-items-center gap-2">
                                    <i class="ti ti-check text-green fs-3"></i>
                                    <span><strong>{{ number_format($plan->max_customers) }}</strong> Pelanggan</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="ti ti-check text-green fs-3"></i>
                                    <span><strong>{{ $plan->max_routers }}</strong> Akses Router MikroTik</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="ti ti-check text-green fs-3"></i>
                                    <span>Server RADIUS Cloud AAA</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="ti ti-check text-green fs-3"></i>
                                    <span>Gratis Auto-VPN SSTP (Anti-CGNAT)</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="ti ti-check text-green fs-3"></i>
                                    <span>Auto-Cut Isolir & Auto-Restore</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="ti ti-check text-green fs-3"></i>
                                    <span>{{ str_contains(strtoupper($plan->name), 'ENTERPRISE') ? '2 WhatsApp Gateway' : '1 WhatsApp Gateway' }}</span>
                                </li>
                            </ul>

                            <div class="mt-auto">
                                @if($isCurrent && !$isExpired)
                                    <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modalPlan-{{ $plan->id }}">
                                        <i class="ti ti-refresh me-1"></i> Perpanjang Paket Ini
                                    </button>
                                @elseif($isUpgrade || $isExpired)
                                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#modalPlan-{{ $plan->id }}">
                                        <i class="ti ti-arrow-up-right me-1"></i> {{ $isExpired ? 'Aktifkan Paket' : 'Upgrade ke ' . $plan->name }}
                                    </button>
                                @elseif($isDowngrade)
                                    <button type="button" class="btn btn-secondary disabled w-100" title="Downgrade hanya dapat diproses oleh Super Admin" disabled>
                                        <i class="ti ti-lock me-1"></i> Downgrade Terkunci
                                    </button>
                                    <div class="text-center mt-2">
                                        <small class="text-muted" style="font-size: 0.72rem;">Hubungi Super Admin jika ingin downgrade paket</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Konfirmasi Upgrade / Perpanjang -->
                <div class="modal modal-blur fade" id="modalPlan-{{ $plan->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="{{ route('tenant.subscription.upgrade') }}" method="POST">
                                @csrf
                                <input type="hidden" name="saas_plan_id" value="{{ $plan->id }}">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Langganan Paket {{ $plan->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Pilih Periode Langganan</label>
                                        <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column gap-2">
                                            <label class="form-selectgroup-item flex-fill">
                                                <input type="radio" name="billing_cycle" value="monthly" class="form-selectgroup-input" checked>
                                                <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                    <div class="me-3">
                                                        <span class="form-selectgroup-check"></span>
                                                    </div>
                                                    <div class="form-selectgroup-label-content d-flex align-items-center justify-content-between w-100">
                                                        <div>
                                                            <div class="font-weight-medium">Bulanan (1 Bulan)</div>
                                                            <div class="text-secondary small">Fleksibel bayar tiap bulan</div>
                                                        </div>
                                                        <div class="fw-bold text-dark fs-3">
                                                            Rp {{ number_format($plan->price, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                            <label class="form-selectgroup-item flex-fill">
                                                <input type="radio" name="billing_cycle" value="yearly" class="form-selectgroup-input">
                                                <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                    <div class="me-3">
                                                        <span class="form-selectgroup-check"></span>
                                                    </div>
                                                    <div class="form-selectgroup-label-content d-flex align-items-center justify-content-between w-100">
                                                        <div>
                                                            <div class="font-weight-medium">Tahunan (12 Bulan) <span class="badge bg-green text-white ms-1">Hemat 20%</span></div>
                                                            <div class="text-secondary small">Rp {{ number_format($plan->getMonthlyEquivalentYearlyAttribute(), 0, ',', '.') }}/bulan</div>
                                                        </div>
                                                        <div class="fw-bold text-dark fs-3">
                                                            Rp {{ number_format($plan->getYearlyPriceAttribute(), 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="alert alert-info py-2 small mb-0">
                                        <i class="ti ti-info-circle me-1"></i> Setelah memilih durasi, Anda akan diarahkan ke halaman <strong>Instruksi Transfer Rekening Resmi Platform</strong> untuk menyelesaikan pembayaran dan mengirimkan bukti transfer.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary ms-auto">
                                        <i class="ti ti-arrow-right me-1"></i> Lanjut ke Pembayaran Transfer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Card Dukungan Khusus Downgrade -->
        <div class="card mt-4 border bg-white">
            <div class="card-body p-3">
                <div class="row align-items-center g-2 text-secondary small">
                    <div class="col-auto">
                        <i class="ti ti-help-circle fs-2 text-primary"></i>
                    </div>
                    <div class="col">
                        <strong class="text-dark">Butuh Downgrade ke Paket yang Lebih Kecil?</strong>
                        <div>
                            Untuk melindungi jaringan Anda dari risiko terputusnya pelanggan aktif yang melebihi batas kuota paket lebih kecil, proses downgrade dilakukan secara terverifikasi melalui Super Admin SaaS. Silakan hubungi tim dukungan kami melalui menu Tiket Bantuan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
