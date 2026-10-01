@extends('layouts.tenant')

@section('title', 'Verifikasi Pembayaran Manual')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Kasir & Keuangan</div>
                <h2 class="page-title">Verifikasi Transfer Manual</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a href="{{ route('tenant.manual-payments.index', ['status' => 'WAITING_VERIFICATION']) }}" class="nav-link {{ $status === 'WAITING_VERIFICATION' ? 'active' : '' }}">
                            <i class="ti ti-clock-pause me-2"></i> Menunggu Verifikasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tenant.manual-payments.index', ['status' => 'APPROVED']) }}" class="nav-link {{ $status === 'APPROVED' ? 'active' : '' }}">
                            <i class="ti ti-check me-2"></i> Disetujui (Lunas)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tenant.manual-payments.index', ['status' => 'REJECTED']) }}" class="nav-link {{ $status === 'REJECTED' ? 'active' : '' }}">
                            <i class="ti ti-x me-2"></i> Ditolak
                        </a>
                    </li>
                </ul>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Nomor Tagihan</th>
                            <th>Pelanggan & Pengirim</th>
                            <th class="d-none d-lg-table-cell">Data Pengirim</th>
                            <th>Nominal</th>
                            <th class="d-none d-md-table-cell">Tgl Transfer</th>
                            <th>Bukti</th>
                            <th class="w-1 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($confirmations as $c)
                            <tr>
                                <td>
                                    <a href="{{ route('tenant.invoices.show', $c->invoice_id) }}" class="fw-bold font-monospace text-decoration-none">
                                        {{ $c->invoice->invoice_number }}
                                    </a>
                                    <div class="text-secondary small d-md-none" style="font-size: 0.72rem;">
                                        {{ $c->transfer_date->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $c->invoice->customer->name ?? '-' }}</div>
                                    <div class="text-secondary small">{{ $c->invoice->customer->customer_code ?? '' }}</div>
                                    <div class="d-lg-none mt-1 text-secondary small" style="font-size: 0.72rem;">
                                        Dari: <strong>{{ $c->sender_name }}</strong> ({{ $c->bank_name }})
                                    </div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <div class="fw-medium">{{ $c->sender_name }}</div>
                                    <div class="text-secondary small">{{ $c->bank_name }} ({{ $c->account_number ?? '-' }})</div>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($c->transfer_amount, 0, ',', '.') }}
                                </td>
                                <td class="d-none d-md-table-cell">{{ $c->transfer_date->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $c->proof_image_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Lihat Bukti Transfer">
                                        <i class="ti ti-photo me-1"></i> Bukti
                                    </a>
                                </td>
                                <td>
                                    @if($c->status === 'WAITING_VERIFICATION')
                                        <div class="btn-list flex-nowrap">
                                            <form action="{{ route('tenant.manual-payments.approve', $c->id) }}" method="POST" onsubmit="return confirm('Apakah dana mutasi bank sudah masuk dan tagihan siap dilunasi?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="ti ti-check me-1"></i> Approve
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modal-reject-{{ $c->id }}">
                                                Tolak
                                            </button>
                                        </div>

                                        <!-- Modal Reject -->
                                        <div class="modal modal-blur fade" id="modal-reject-{{ $c->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('tenant.manual-payments.reject', $c->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Tolak Konfirmasi Pembayaran</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label required">Alasan Penolakan</label>
                                                                <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Contoh: Bukti transfer tidak valid atau dana tidak ditemukan di mutasi rekening."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <x-badge :status="$c->status" />
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-empty-state 
                                        title="Tidak Ada Konfirmasi Transfer" 
                                        subtitle="Belum ada bukti pembayaran transfer baru yang perlu diverifikasi."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($confirmations->hasPages())
                <div class="card-footer d-flex align-items-center">
                    {{ $confirmations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
