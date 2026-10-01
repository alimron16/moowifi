@extends('layouts.tenant')

@section('title', 'Metode Pembayaran Manual')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Konfigurasi Kanal Bayar</div>
                <h2 class="page-title">Rekening Transfer & E-Wallet Manual</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-manual">
                    <i class="ti ti-plus me-1"></i> Tambah Rekening Baru
                </button>
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
                            <th>Bank / Channel</th>
                            <th>Nama Akun / Kanal</th>
                            <th>Nomor Rekening</th>
                            <th>Atas Nama (Pemilik)</th>
                            <th>Status</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($manualMethods as $m)
                            <tr>
                                <td>
                                    <span class="badge bg-blue-lt fw-bold">{{ $m->provider }}</span>
                                </td>
                                <td>{{ $m->name }}</td>
                                <td class="font-monospace fs-3 fw-bold">{{ $m->account_number }}</td>
                                <td>{{ $m->account_name }}</td>
                                <td>
                                    <x-badge :status="$m->is_active ? 'ACTIVE' : 'INACTIVE'" :dot="true" />
                                </td>
                                <td>
                                    <form action="{{ route('tenant.payment-methods.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus rekening ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-ghost-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <x-empty-state 
                                        title="Belum Ada Rekening Manual" 
                                        subtitle="Tambahkan rekening bank atau e-wallet untuk menerima transfer manual dari pelanggan."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Manual Method -->
<div class="modal modal-blur fade" id="modal-add-manual" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('tenant.payment-methods.store-manual') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Rekening Transfer Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label required">Jenis Bank / E-Wallet</label>
                            <select name="provider" class="form-select" required>
                                <option value="BCA">Bank BCA</option>
                                <option value="MANDIRI">Bank Mandiri</option>
                                <option value="BRI">Bank BRI</option>
                                <option value="BNI">Bank BNI</option>
                                <option value="BSI">Bank BSI</option>
                                <option value="DANA">DANA</option>
                                <option value="OVO">OVO</option>
                                <option value="GOPAY">GoPay</option>
                                <option value="QRIS">QRIS Statis</option>
                                <option value="CASH">Setor Tunai</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label required">Label Tampilan</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: BCA BudiNet" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Nomor Rekening / No. E-Wallet</label>
                        <input type="text" name="account_number" class="form-control" placeholder="1234567890" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Atas Nama Pemilik Rekening</label>
                        <input type="text" name="account_name" class="form-control" placeholder="Nama sesuai buku tabungan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Petunjuk Transfer</label>
                        <textarea name="instructions" class="form-control" rows="2" placeholder="Sertakan nomor invoice pada berita transfer"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Rekening</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
