@extends('layouts.super-admin')

@section('title', 'Paket SaaS Platform')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Monetisasi SaaS</div>
                <h2 class="page-title">Paket Langganan Platform</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-plan">
                    <i class="ti ti-plus me-1"></i> Tambah Paket SaaS
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            @forelse($plans as $p)
                <div class="col-md-4">
                    <div class="card card-md h-100 d-flex flex-column">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="badge {{ $p->is_active ? 'bg-success-lt' : 'bg-secondary-lt' }}">
                                {{ $p->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-icon btn-ghost-secondary btn-sm" data-bs-toggle="dropdown" aria-label="Menu Aksi">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-edit-plan-{{ $p->id }}">
                                        <i class="ti ti-edit me-2"></i> Edit Paket
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('super-admin.plans.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket {{ $p->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="ti ti-trash me-2"></i> Hapus Paket
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-center flex-grow-1">
                            <div class="text-uppercase text-secondary font-weight-bold">{{ $p->code }}</div>
                            <h2 class="h1 my-3">{{ $p->name }}</h2>
                            <div class="display-6 font-weight-bold my-3 text-primary">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                                <span class="fs-4 text-secondary fw-normal">/bulan</span>
                            </div>
                            <ul class="list-unstyled lh-lg text-start my-4">
                                <li>
                                    <i class="ti ti-check text-success me-2"></i>
                                    Maksimal <strong>{{ $p->max_customers == 0 ? 'Unlimited' : $p->max_customers }} Pelanggan</strong>
                                </li>
                                <li>
                                    <i class="ti ti-check text-success me-2"></i>
                                    Maksimal <strong>{{ $p->max_routers }} Router MikroTik</strong>
                                </li>
                                <li>
                                    <i class="ti ti-check text-success me-2"></i>
                                    Billing & Invoice Otomatis
                                </li>
                                <li>
                                    <i class="ti ti-check text-success me-2"></i>
                                    Payment Gateway & Manual
                                </li>
                                <li>
                                    <i class="ti ti-check text-success me-2"></i>
                                    Auto-Cut & Auto-Restore MikroTik
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center bg-body-tertiary">
                            <span class="text-secondary small">
                                Digunakan: <strong>{{ $p->subscriptions_count }} tenant</strong>
                            </span>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-edit-plan-{{ $p->id }}">
                                <i class="ti ti-edit me-1"></i> Edit Paket
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Plan -->
                <div class="modal modal-blur fade" id="modal-edit-plan-{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="{{ route('super-admin.plans.update', $p->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Paket: {{ $p->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-2 mb-3">
                                        <div class="col-8">
                                            <label class="form-label required">Nama Paket</label>
                                            <input type="text" name="name" class="form-control" value="{{ $p->name }}" required>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label required">Kode</label>
                                            <input type="text" name="code" class="form-control text-uppercase" value="{{ $p->code }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Harga Langganan Bulanan (Rp)</label>
                                        <input type="number" name="price" class="form-control" value="{{ (int)$p->price }}" required>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label required">Limit Pelanggan</label>
                                            <input type="number" name="max_customers" class="form-control" value="{{ $p->max_customers }}" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label required">Limit Router</label>
                                            <input type="number" name="max_routers" class="form-control" value="{{ $p->max_routers }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $p->is_active ? 'checked' : '' }}>
                                            <span class="form-check-label">Aktifkan Paket (Bisa Dipilih Tenant)</span>
                                        </label>
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
                <div class="col-12">
                    <x-empty-state 
                        title="Belum Ada Paket SaaS" 
                        subtitle="Buat paket langganan untuk para pemilik RT/RW Net."
                    />
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Add Plan -->
<div class="modal modal-blur fade" id="modal-add-plan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('super-admin.plans.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Buat Paket SaaS Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-8">
                            <label class="form-label required">Nama Paket</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Paket Pro" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label required">Kode</label>
                            <input type="text" name="code" class="form-control text-uppercase" placeholder="PRO" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Harga Langganan Bulanan (Rp)</label>
                        <input type="number" name="price" class="form-control" placeholder="150000" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label required">Limit Pelanggan</label>
                            <input type="number" name="max_customers" class="form-control" value="200" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label required">Limit Router</label>
                            <input type="number" name="max_routers" class="form-control" value="2" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
