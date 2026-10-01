@extends('layouts.tenant')

@section('title', 'Terbitkan Tagihan Baru')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Penerbitan Billing</div>
                <h2 class="page-title">Generate Tagihan Internet</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.invoices.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body" x-data="{ mode: 'single' }">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                            <li class="nav-item">
                                <a href="#tab-single" class="nav-link active" data-bs-toggle="tab" @click="mode = 'single'">
                                    <i class="ti ti-user me-2"></i> Tagihan Per Pelanggan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab-batch" class="nav-link" data-bs-toggle="tab" @click="mode = 'batch'">
                                    <i class="ti ti-users-group me-2"></i> Generate Massal Awal Bulan
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Tab Single Customer -->
                            <div class="tab-pane active show" id="tab-single">
                                <form action="{{ route('tenant.invoices.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="mode" value="single">

                                    <div class="mb-3">
                                        <label class="form-label required">Pilih Pelanggan</label>
                                        <select name="customer_id" class="form-select" required>
                                            <option value="">Pilih pelanggan...</option>
                                            @foreach($customers as $c)
                                                <option value="{{ $c->id }}">
                                                    {{ $c->customer_code }} - {{ $c->name }} (Paket: {{ $c->package->name ?? 'None' }} - Rp {{ number_format($c->package->price ?? 0, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label required">Awal Periode</label>
                                            <input type="date" name="period_start" class="form-control" value="{{ now()->startOfMonth()->toDateString() }}" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label required">Akhir Periode</label>
                                            <input type="date" name="period_end" class="form-control" value="{{ now()->endOfMonth()->toDateString() }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required">Tanggal Jatuh Tempo</label>
                                        <input type="date" name="due_date" class="form-control" value="{{ now()->startOfMonth()->addDays(9)->toDateString() }}" required>
                                    </div>

                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="ti ti-receipt me-1"></i> Buat Tagihan Sekarang
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Tab Batch Generate -->
                            <div class="tab-pane" id="tab-batch">
                                <form action="{{ route('tenant.invoices.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="mode" value="batch">

                                    <div class="alert alert-info">
                                        <div class="d-flex">
                                            <i class="ti ti-info-circle fs-2 me-2"></i>
                                            <div>
                                                Sistem akan secara otomatis memeriksa seluruh pelanggan aktif dan menerbitkan invoice bulanan baru. Pelanggan yang sudah memiliki invoice pada bulan tersebut akan dilewati secara otomatis untuk mencegah tagihan ganda.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label required">Bulan Penagihan</label>
                                            <input type="month" name="billing_month" class="form-control" value="{{ now()->format('Y-m') }}" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label required">Tanggal Jatuh Tempo (Hari ke-)</label>
                                            <input type="number" name="due_day" class="form-control" value="10" min="1" max="28" required>
                                        </div>
                                    </div>

                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Jalankan proses generate tagihan massal untuk seluruh pelanggan aktif?')">
                                            <i class="ti ti-player-play me-1"></i> Jalankan Generate Tagihan Serentak
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
