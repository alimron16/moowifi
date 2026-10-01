@extends('layouts.tenant')

@section('title', 'Paket Internet')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Katalog Layanan</div>
                <h2 class="page-title">Paket Internet</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-package">
                    <i class="ti ti-plus me-1"></i>
                    Tambah Paket Baru
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
                            <th>Paket Layanan</th>
                            <th class="d-none d-md-table-cell">Kecepatan</th>
                            <th class="d-none d-lg-table-cell">Profil MikroTik</th>
                            <th>Harga</th>
                            <th class="d-none d-md-table-cell">Pelanggan</th>
                            <th>Status</th>
                            <th class="w-1 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $p)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $p->name }}</div>
                                    <!-- Mobile-only speed & customer chips -->
                                    <div class="d-md-none mt-1 d-flex flex-wrap gap-1 align-items-center">
                                        <span class="badge bg-blue-lt" style="font-size: 0.7rem;">
                                            <i class="ti ti-arrow-down"></i> {{ $p->download_speed }} / <i class="ti ti-arrow-up"></i> {{ $p->upload_speed }}
                                        </span>
                                        <span class="badge bg-azure-lt" style="font-size: 0.7rem;">
                                            {{ $p->customers_count }} User
                                        </span>
                                    </div>
                                    <div class="text-secondary small d-none d-md-block">{{ $p->description ?? '-' }}</div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="badge bg-blue-lt">
                                        <i class="ti ti-arrow-down me-1"></i> {{ $p->download_speed }}
                                    </span>
                                    <span class="badge bg-purple-lt ms-1">
                                        <i class="ti ti-arrow-up me-1"></i> {{ $p->upload_speed }}
                                    </span>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <code class="text-dark">{{ $p->mikrotik_profile ?? 'default' }}</code>
                                </td>
                                <td class="fw-bold text-success">
                                    Rp {{ number_format($p->price, 0, ',', '.') }}
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <a href="{{ route('tenant.customers.index') }}" class="badge bg-azure-lt text-decoration-none">
                                        {{ $p->customers_count }} Pelanggan
                                    </a>
                                </td>
                                <td>
                                    <x-badge :status="$p->status" :dot="true" />
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-ghost-primary" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $p->id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('tenant.packages.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus paket ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-ghost-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit Package -->
                            <div class="modal modal-blur fade" id="modal-edit-{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('tenant.packages.update', $p->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Paket: {{ $p->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label required">Nama Paket</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $p->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label required">Harga Bulanan (Rp)</label>
                                                    <input type="number" name="price" class="form-control" value="{{ (int)$p->price }}" required>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label required">Download Speed</label>
                                                        <input type="text" name="download_speed" class="form-control" value="{{ $p->download_speed }}" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label required">Upload Speed</label>
                                                        <input type="text" name="upload_speed" class="form-control" value="{{ $p->upload_speed }}" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Profile di MikroTik</label>
                                                    <input type="text" name="mikrotik_profile" class="form-control" value="{{ $p->mikrotik_profile }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label required">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="ACTIVE" {{ $p->status === 'ACTIVE' ? 'selected' : '' }}>Aktif</option>
                                                        <option value="INACTIVE" {{ $p->status === 'INACTIVE' ? 'selected' : '' }}>Non-Aktif</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea name="description" class="form-control" rows="2">{{ $p->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-empty-state 
                                        title="Belum Ada Paket Internet" 
                                        subtitle="Tambahkan paket langganan internet pertama Anda."
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

<!-- Modal Add Package -->
<div class="modal modal-blur fade" id="modal-add-package" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('tenant.packages.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Paket Internet Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Paket</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: 10 Mbps Hemat" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Harga Bulanan (Rp)</label>
                        <input type="number" name="price" class="form-control" placeholder="100000" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label required">Download Speed</label>
                            <input type="text" name="download_speed" class="form-control" placeholder="10M" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label required">Upload Speed</label>
                            <input type="text" name="upload_speed" class="form-control" placeholder="5M" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Profile di MikroTik</label>
                        <input type="text" name="mikrotik_profile" class="form-control" placeholder="profile_10m">
                        <small class="form-hint">Harus sama dengan nama profile PPPoE di RouterOS MikroTik.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Keterangan paket"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> Simpan Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
