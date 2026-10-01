@extends('layouts.tenant')

@section('title', 'Tambah Pelanggan Baru')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Formulir Pendaftaran</div>
                <h2 class="page-title">Tambah Pelanggan Baru</h2>
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
        <form action="{{ route('tenant.customers.store') }}" method="POST">
            @csrf
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
                                <input type="text" name="customer_code" class="form-control @error('customer_code') is-invalid @enderror" value="{{ old('customer_code', $suggestedCode) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama pelanggan" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Nomor WhatsApp / HP</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="08123456789" required>
                                <small class="form-hint">Nomor aktif untuk pengiriman invoice dan notifikasi tagihan.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email (Opsional)</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="pelanggan@email.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat Pemasangan</label>
                                <textarea name="address" rows="3" class="form-control" placeholder="Alamat rumah / lokasi ODP / RT / RW">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jaringan & MikroTik -->
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Layanan & Integrasi MikroTik</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Pilihan Paket Internet</label>
                                <select name="package_id" class="form-select @error('package_id') is-invalid @enderror" required>
                                    <option value="">Pilih Paket...</option>
                                    @foreach($packages as $p)
                                        <option value="{{ $p->id }}" {{ old('package_id') == $p->id ? 'selected' : '' }}>
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
                                        <option value="{{ $r->id }}" {{ old('router_id') == $r->id ? 'selected' : '' }}>
                                            {{ $r->name }} ({{ $r->connection_type }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label required">Jenis Koneksi</label>
                                    <select name="connection_type" class="form-select">
                                        <option value="PPPOE">PPPoE Secret</option>
                                        <option value="HOTSPOT">Hotspot User</option>
                                        <option value="STATIC_IP">Static IP / Queue</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Username MikroTik</label>
                                    <input type="text" name="mikrotik_username" class="form-control" value="{{ old('mikrotik_username') }}" placeholder="User PPP">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password MikroTik</label>
                                <input type="password" name="mikrotik_password" class="form-control" placeholder="Password PPP">
                            </div>
                            <div class="mb-3">
                                <label class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="sync_to_mikrotik" value="1" checked>
                                    <span class="form-check-label">Sinkronkan akun PPP ke MikroTik secara otomatis</span>
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
                                        <option value="PREPAID">Prabayar (Prepaid)</option>
                                        <option value="POSTPAID">Pascabayar (Postpaid)</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label required">Tgl Tagih</label>
                                    <input type="number" name="billing_day" class="form-control" value="{{ old('billing_day', 1) }}" min="1" max="28" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label required">Jatuh Tempo</label>
                                    <input type="number" name="due_day" class="form-control" value="{{ old('due_day', 10) }}" min="1" max="28" required>
                                </div>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label required">Toleransi Hari (Grace)</label>
                                    <input type="number" name="grace_period_days" class="form-control" value="{{ old('grace_period_days', 3) }}" min="0" max="30" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">&nbsp;</label>
                                    <label class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="auto_cut_enabled" value="1" checked>
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
                    <i class="ti ti-device-floppy me-1"></i> Simpan Data Pelanggan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
