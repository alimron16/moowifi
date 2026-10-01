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
                    <div class="card card-md">
                        <div class="card-body text-center">
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
                                    Payment Gateway Duitku & Manual
                                </li>
                                <li>
                                    <i class="ti ti-check text-success me-2"></i>
                                    Auto-Cut & Auto-Restore MikroTik
                                </li>
                            </ul>
                            <div class="text-secondary small">
                                Digunakan oleh {{ $p->subscriptions_count }} tenant
                            </div>
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
