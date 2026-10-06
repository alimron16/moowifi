@extends('layouts.tenant')

@section('title', 'Pembayaran Langganan SaaS')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">SaaS Invoice & Billing</div>
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
        <div class="row row-cards justify-content-center">
            <!-- Kolom Rincian Tagihan & Rekening Pembayaran -->
            <div class="col-12 col-lg-7">
                <!-- Card Rincian Tagihan -->
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-secondary small">Nomor Tagihan:</span>
                            <div class="fw-bold font-monospace text-dark">{{ $subscription->order_number ?? ('ORD-SUB-' . $subscription->id) }}</div>
                        </div>
                        <div>
                            @if($subscription->status === 'WAITING_VERIFICATION')
                                <span class="badge bg-yellow text-dark px-2 py-1">
                                    <i class="ti ti-clock me-1"></i> Menunggu Verifikasi Admin
                                </span>
                            @elseif($subscription->status === 'ACTIVE')
                                <span class="badge bg-green text-white px-2 py-1">
                                    <i class="ti ti-check me-1"></i> Lunas & Aktif
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-2 py-1">
                                    <i class="ti ti-wallet me-1"></i> Menunggu Pembayaran
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col">
                                <h3 class="mb-1 text-primary">Paket {{ $subscription->saasPlan?->name ?? 'Pro' }}</h3>
                                <div class="text-secondary small">
                                    Periode: <strong>{{ $subscription->billing_cycle === 'yearly' ? 'Tahunan (12 Bulan - Hemat 20%)' : 'Bulanan (1 Bulan)' }}</strong>
                                </div>
                                <div class="text-muted small">
                                    Kapasitas: {{ number_format($subscription->saasPlan?->max_customers ?? 0) }} Pelanggan &bull; {{ $subscription->saasPlan?->max_routers ?? 1 }} Router MikroTik
                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <span class="text-secondary small">Total yang Harus Ditransfer:</span>
                                <div class="display-6 fw-bold text-success">
                                    Rp {{ number_format($subscription->amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info py-2 small mb-0">
                            <i class="ti ti-info-circle me-1"></i> Silakan transfer <strong>tepat sesuai nominal di atas</strong> ke salah satu rekening resmi platform berikut agar proses verifikasi berjalan instan.
                        </div>
                    </div>
                </div>

                <!-- Card Rekening Tujuan Transfer Super Admin -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-building-bank me-1 text-primary"></i> Rekening Resmi Pembayaran Platform
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($manualBanks as $index => $bank)
                                <div class="list-group-item p-3">
                                    <div class="row align-items-center g-2">
                                        <div class="col-auto">
                                            <span class="avatar bg-primary-lt text-primary fw-bold rounded">
                                                {{ substr($bank['bank_name'] ?? 'BANK', 0, 4) }}
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="fw-bold text-dark fs-3 mb-0">{{ $bank['bank_name'] ?? 'Bank' }}</div>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <span class="font-monospace fs-2 fw-bold text-dark" id="accNumber-{{ $index }}">
                                                    {{ $bank['account_number'] ?? '-' }}
                                                </span>
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="copyAccNumber('accNumber-{{ $index }}')">
                                                    <i class="ti ti-copy me-1"></i> Salin
                                                </button>
                                            </div>
                                            <div class="small text-secondary mt-1">
                                                Atas Nama: <strong class="text-dark">{{ $bank['account_name'] ?? '-' }}</strong>
                                            </div>
                                            @if(!empty($bank['instructions']))
                                                <div class="small text-muted mt-1 fst-italic">
                                                    {{ $bank['instructions'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">
                                    Rekening pembayaran manual belum dikonfigurasi oleh Super Admin.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Form Konfirmasi Pembayaran & Upload Bukti Transfer -->
            <div class="col-12 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-upload me-1 text-primary"></i> Konfirmasi Bukti Pembayaran
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($subscription->status === 'WAITING_VERIFICATION')
                            <div class="alert alert-success border-success-subtle bg-success-lt mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="ti ti-circle-check fs-2 text-success"></i>
                                    <strong class="text-dark">Bukti Pembayaran Berhasil Diunggah!</strong>
                                </div>
                                <p class="small text-secondary mb-2">
                                    Tim Super Admin sedang memverifikasi pembayaran Anda. Kuota dan akses paket akan aktif otomatis setelah verifikasi disetujui (estimasi 5-15 menit).
                                </p>
                                <div class="small text-secondary">
                                    Metode Pembayaran: <strong class="text-dark">{{ $subscription->payment_method }}</strong>
                                </div>
                            </div>

                            @if($subscription->proof_path)
                                <div class="mb-3 text-center">
                                    <span class="text-secondary small d-block mb-1">Bukti Transfer yang Dikirim:</span>
                                    <a href="{{ asset('storage/' . $subscription->proof_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="ti ti-file-text me-1"></i> Lihat File Bukti Transfer
                                    </a>
                                </div>
                            @endif

                            <div class="hr my-3"></div>

                            <div class="text-center">
                                <span class="text-secondary small d-block mb-2">Ingin konfirmasi lebih cepat?</span>
                                @php
                                    $adminPhone = preg_replace('/[^0-9]/', '', $platformProfile['contact_phone'] ?? '081122334455');
                                    if (str_starts_with($adminPhone, '0')) {
                                        $adminPhone = '62' . substr($adminPhone, 1);
                                    }
                                    $waText = urlencode("Halo Admin MooWiFi, saya pemilik RT/RW Net ({$tenant->name}) telah melakukan transfer langganan paket {$subscription->saasPlan?->name} nomor pesanan {$subscription->order_number} sebesar Rp " . number_format($subscription->amount, 0, ',', '.') . ". Mohon bantuannya untuk verifikasi.");
                                @endphp
                                <a href="https://wa.me/{{ $adminPhone }}?text={{ $waText }}" target="_blank" class="btn btn-success w-100">
                                    <i class="ti ti-brand-whatsapp me-1"></i> Konfirmasi ke WhatsApp Admin
                                </a>
                            </div>
                        @else
                            <form action="{{ route('tenant.subscription.confirm-payment', $subscription->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label required">Bank Tujuan Transfer yang Digunakan</label>
                                    <select name="payment_method" class="form-select" required>
                                        <option value="" disabled selected>-- Pilih Bank Tujuan --</option>
                                        @foreach($manualBanks as $b)
                                            <option value="{{ $b['bank_name'] }} - {{ $b['account_number'] }}">
                                                {{ $b['bank_name'] }} ({{ $b['account_number'] }} a.n {{ $b['account_name'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Unggah Bukti Transfer / Struk</label>
                                    <input type="file" name="proof" class="form-control" accept="image/*,.pdf" required>
                                    <small class="form-hint">Format file: JPG, PNG, atau PDF (Maksimal 3 MB).</small>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="ti ti-send me-1"></i> Kirim Bukti Transfer
                                </button>
                            </form>

                            <div class="hr my-3"></div>

                            <div class="text-center">
                                <span class="text-secondary small d-block mb-2">Butuh bantuan transaksi?</span>
                                @php
                                    $adminPhone = preg_replace('/[^0-9]/', '', $platformProfile['contact_phone'] ?? '081122334455');
                                    if (str_starts_with($adminPhone, '0')) {
                                        $adminPhone = '62' . substr($adminPhone, 1);
                                    }
                                @endphp
                                <a href="https://wa.me/{{ $adminPhone }}" target="_blank" class="btn btn-outline-success btn-sm w-100">
                                    <i class="ti ti-brand-whatsapp me-1"></i> Hubungi Dukungan Super Admin
                                </a>
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
    alert('Nomor rekening ' + text + ' berhasil disalin ke clipboard.');
}
</script>
@endsection
