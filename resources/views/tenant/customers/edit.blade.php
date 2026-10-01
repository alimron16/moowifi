@extends('layouts.tenant')

@section('title', 'Edit Pelanggan: ' . $customer->name)

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Perbarui Data</div>
                <h2 class="page-title">Edit Pelanggan: {{ $customer->name }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.customers.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <form action="{{ route('tenant.customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row row-cards">
                <!-- Info Pelanggan -->
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Identitas & Kontak</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Kode Pelanggan</label>
                                <input type="text" name="customer_code" class="form-control @error('customer_code') is-invalid @enderror" value="{{ old('customer_code', $customer->customer_code) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $customer->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Nomor WhatsApp / HP</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $customer->phone) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $customer->email) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat Pemasangan</label>
                                <textarea name="address" rows="3" class="form-control">{{ old('address', $customer->address) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Status Layanan</label>
                                <select name="status" class="form-select">
                                    <option value="ACTIVE" {{ $customer->status === 'ACTIVE' ? 'selected' : '' }}>ACTIVE (Aktif)</option>
                                    <option value="UNPAID" {{ $customer->status === 'UNPAID' ? 'selected' : '' }}>UNPAID (Menunggak)</option>
                                    <option value="OVERDUE" {{ $customer->status === 'OVERDUE' ? 'selected' : '' }}>OVERDUE (Lewat Tempo)</option>
                                    <option value="ISOLATED" {{ $customer->status === 'ISOLATED' ? 'selected' : '' }}>ISOLATED (Terisolir)</option>
                                    <option value="SUSPENDED" {{ $customer->status === 'SUSPENDED' ? 'selected' : '' }}>SUSPENDED (Cuti)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jaringan & MikroTik -->
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Layanan & MikroTik</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Pilihan Paket Internet</label>
                                <select name="package_id" class="form-select" required>
                                    @foreach($packages as $p)
                                        <option value="{{ $p->id }}" {{ old('package_id', $customer->package_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} - Rp {{ number_format($p->price, 0, ',', '.') }} ({{ $p->download_speed }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Router MikroTik</label>
                                <select name="router_id" class="form-select">
                                    <option value="">Pilih Router...</option>
                                    @foreach($routers as $r)
                                        <option value="{{ $r->id }}" {{ old('router_id', $customer->router_id) == $r->id ? 'selected' : '' }}>
                                            {{ $r->name }} ({{ $r->connection_type }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label required">Jenis Koneksi</label>
                                    <select name="connection_type" class="form-select">
                                        <option value="PPPOE" {{ $customer->connection_type === 'PPPOE' ? 'selected' : '' }}>PPPoE Secret</option>
                                        <option value="HOTSPOT" {{ $customer->connection_type === 'HOTSPOT' ? 'selected' : '' }}>Hotspot User</option>
                                        <option value="STATIC_IP" {{ $customer->connection_type === 'STATIC_IP' ? 'selected' : '' }}>Static IP / Queue</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Username MikroTik</label>
                                    <input type="text" name="mikrotik_username" class="form-control" value="{{ old('mikrotik_username', $customer->mikrotik_username) }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password MikroTik (Kosongkan jika tidak diubah)</label>
                                <input type="password" name="mikrotik_password" class="form-control" placeholder="••••••••">
                            </div>
                            <div class="mb-3">
                                <label class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="sync_to_mikrotik" value="1" checked>
                                    <span class="form-check-label">Update akun ke MikroTik saat disimpan</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Policy -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Ketentuan Billing</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label required">Tipe Billing</label>
                                    <select name="billing_type" class="form-select">
                                        <option value="PREPAID" {{ $customer->billing_type === 'PREPAID' ? 'selected' : '' }}>Prabayar (Prepaid)</option>
                                        <option value="POSTPAID" {{ $customer->billing_type === 'POSTPAID' ? 'selected' : '' }}>Pascabayar (Postpaid)</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label required">Tgl Tagih</label>
                                    <input type="number" name="billing_day" class="form-control" value="{{ old('billing_day', $customer->billing_day) }}" min="1" max="28" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label required">Jatuh Tempo</label>
                                    <input type="number" name="due_day" class="form-control" value="{{ old('due_day', $customer->due_day) }}" min="1" max="28" required>
                                </div>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label required">Toleransi Hari (Grace)</label>
                                    <input type="number" name="grace_period_days" class="form-control" value="{{ old('grace_period_days', $customer->grace_period_days) }}" min="0" max="30" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">&nbsp;</label>
                                    <label class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="auto_cut_enabled" value="1" {{ $customer->auto_cut_enabled ? 'checked' : '' }}>
                                        <span class="form-check-label">Aktifkan Auto-Cut</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2 bg-transparent border-0 px-0">
                <a href="{{ route('tenant.customers.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
