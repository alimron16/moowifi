@extends('layouts.super-admin')

@section('title', 'Pengumuman Platform (Announcements)')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Broadcast Komunikasi</div>
                <h2 class="page-title">Pengumuman & Pemberitahuan Platform</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-announcement">
                    <i class="ti ti-plus me-1"></i> Buat Pengumuman Baru
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pengumuman Aktif ke Seluruh Tenant</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Judul Pengumuman</th>
                            <th>Tipe / Kategori</th>
                            <th>Tanggal Siar</th>
                            <th>Target Penerima</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $a)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $a['title'] }}</div>
                                    <div class="text-secondary small">{{ $a['message'] }}</div>
                                </td>
                                <td>
                                    @php
                                        $badgeColor = match($a['type'] ?? 'info') {
                                            'warning' => 'bg-warning-lt',
                                            'danger' => 'bg-danger-lt',
                                            'success' => 'bg-success-lt',
                                            default => 'bg-blue-lt',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeColor }} text-uppercase">{{ $a['type'] ?? 'INFO' }}</span>
                                </td>
                                <td class="font-monospace small text-secondary">
                                    {{ $a['created_at'] ?? now()->format('d/m/Y') }}
                                </td>
                                <td>Semua Tenant RT/RW Net</td>
                                <td><span class="badge bg-success-lt">TAYANG</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td>
                                    <div class="fw-bold">Pembaruan Sistem Billing Engine v2.0</div>
                                    <div class="text-secondary small">Integrasi multi-gateway Duitku, Midtrans, Xendit, Tripay & WhatsApp QR Scan.</div>
                                </td>
                                <td><span class="badge bg-blue-lt">FITUR BARU</span></td>
                                <td class="font-monospace small text-secondary">02/10/2026</td>
                                <td>Semua Tenant RT/RW Net</td>
                                <td><span class="badge bg-success-lt">TAYANG</span></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Announcement -->
<div class="modal modal-blur fade" id="modal-add-announcement" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('super-admin.announcements.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Buat Pengumuman Platform</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Judul Pengumuman</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Pemeliharaan Server Rutin" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Kategori / Sifat Pengumuman</label>
                        <select name="type" class="form-select" required>
                            <option value="info">Informasi / Fitur Baru (Biru)</option>
                            <option value="warning">Peringatan / Maintenance (Kuning)</option>
                            <option value="danger">Penting / Darurat (Merah)</option>
                            <option value="success">Pemberitahuan Sukses (Hijau)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Isi Pengumuman</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Tuliskan rincian pengumuman yang akan tampil di dashboard seluruh pemilik RT/RW Net..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Siarkan Pengumuman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
