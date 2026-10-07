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
                            <th>Nomor Rekening / NMID</th>
                            <th>Atas Nama (Pemilik)</th>
                            <th>QR Code</th>
                            <th>Status</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($manualMethods as $m)
                            <tr>
                                <td>
                                    <span class="badge {{ $m->provider === 'QRIS' ? 'bg-purple-lt text-purple' : 'bg-blue-lt text-blue' }} fw-bold">
                                        <i class="ti {{ $m->provider === 'QRIS' ? 'ti-qrcode' : 'ti-building-bank' }} me-1"></i>
                                        {{ $m->provider }}
                                    </span>
                                </td>
                                <td class="fw-medium">{{ $m->name }}</td>
                                <td class="font-monospace fs-3 fw-bold">{{ $m->account_number }}</td>
                                <td>{{ $m->account_name }}</td>
                                <td>
                                    @if($m->qr_code_image)
                                        <a href="{{ asset('storage/' . $m->qr_code_image) }}" target="_blank" class="d-inline-flex align-items-center gap-1 badge bg-purple-lt text-purple text-decoration-none py-1 px-2" title="Klik untuk melihat gambar QRIS">
                                            <i class="ti ti-qrcode fs-3"></i>
                                            <span>Lihat QR</span>
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
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
                                <td colspan="7">
                                    <x-empty-state 
                                        title="Belum Ada Rekening Manual" 
                                        subtitle="Tambahkan rekening bank, e-wallet, atau QRIS statis untuk menerima transfer pembayaran dari pelanggan."
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
        <div class="modal-content" x-data="{ 
            provider: 'BCA', 
            qrPreview: null,
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => { this.qrPreview = e.target.result; };
                    reader.readAsDataURL(file);
                } else {
                    this.qrPreview = null;
                }
            }
        }">
            <form action="{{ route('tenant.payment-methods.store-manual') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ti ti-wallet me-2 text-primary"></i> Tambah Rekening / QRIS Manual
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label required">Jenis Bank / E-Wallet</label>
                            <select name="provider" class="form-select" x-model="provider" required>
                                <option value="BCA">Bank BCA</option>
                                <option value="MANDIRI">Bank Mandiri</option>
                                <option value="BRI">Bank BRI</option>
                                <option value="BNI">Bank BNI</option>
                                <option value="BSI">Bank BSI</option>
                                <option value="DANA">DANA</option>
                                <option value="OVO">OVO</option>
                                <option value="GOPAY">GoPay</option>
                                <option value="QRIS">QRIS Statis (Barcode)</option>
                                <option value="CASH">Setor Tunai</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label required">Label Tampilan</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: BCA BudiNet / QRIS Toko" required>
                        </div>
                    </div>

                    <!-- Upload Gambar QRIS (Ditampilkan jika memilih QRIS) -->
                    <div class="mb-3 p-3 border border-purple rounded bg-purple-lt" x-show="provider === 'QRIS'" x-cloak>
                        <label class="form-label fw-bold text-purple mb-1">
                            <i class="ti ti-qrcode me-1"></i> Unggah Gambar QRIS Statis
                        </label>
                        <input type="file" name="qr_code_image" class="form-control" accept="image/jpeg,image/png,image/webp" @change="handleFileSelect($event)">
                        <small class="form-hint text-secondary mt-1">
                            Format file: JPG, PNG, atau WebP (Maks. 3 MB). Gambar barcode ini akan langsung ditampilkan kepada pelanggan di halaman pembayaran invoice.
                        </small>
                        <template x-if="qrPreview">
                            <div class="mt-2 text-center p-2 bg-white rounded border">
                                <span class="d-block text-muted small mb-1">Pratinjau Gambar QRIS:</span>
                                <img :src="qrPreview" class="img-fluid rounded" style="max-height: 180px; object-fit: contain;">
                            </div>
                        </template>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" :class="{ 'required': provider !== 'QRIS' }">
                            <span x-text="provider === 'QRIS' ? 'Nomor NMID / ID Merchant (Opsional)' : 'Nomor Rekening / No. E-Wallet'"></span>
                        </label>
                        <input type="text" name="account_number" class="form-control" :placeholder="provider === 'QRIS' ? 'Contoh: ID1020000000000' : '1234567890'" :required="provider !== 'QRIS'">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" :class="{ 'required': provider !== 'QRIS' }">
                            <span x-text="provider === 'QRIS' ? 'Atas Nama Merchant / Usaha (Opsional)' : 'Atas Nama Pemilik Rekening'"></span>
                        </label>
                        <input type="text" name="account_name" class="form-control" :placeholder="provider === 'QRIS' ? 'Nama sesuai akun QRIS' : 'Nama sesuai buku tabungan'" :required="provider !== 'QRIS'">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Petunjuk Transfer / Instruksi Pembayaran</label>
                        <textarea name="instructions" class="form-control" rows="2" placeholder="Contoh: Cantumkan ID Pelanggan pada berita transfer, atau scan barcode QRIS lalu kirim bukti pembayaran."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Rekening
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
