@extends('layouts.tenant')

@section('title', 'Dashboard Operasional')

@section('tenant-content')
<!-- Page Header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle text-secondary">Ikhtisar Operasional & Penagihan</div>
                <h2 class="page-title text-dark">{{ $tenant?->name ?? 'Dashboard RT/RW Net' }}</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('tenant.customers.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <i class="ti ti-user-plus me-1"></i>
                        Tambah Pelanggan
                    </a>
                    <a href="{{ route('tenant.invoices.create') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-receipt me-1"></i>
                        Terbitkan Tagihan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- System Health & Automation Status Bar -->
        <div class="card mb-4 bg-white border">
            <div class="card-body py-2 px-3">
                <div class="row align-items-center g-3 text-secondary small">
                    <div class="col-12 col-md-3 d-flex align-items-center gap-2">
                        <span class="status-dot status-dot-animated {{ $routers->where('status', 'ONLINE')->count() > 0 ? 'bg-green' : 'bg-red' }}"></span>
                        <div>
                            <span class="text-dark fw-medium">Router MikroTik:</span> 
                            <span>{{ $routers->where('status', 'ONLINE')->count() }}/{{ $routers->count() }} Online</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 d-flex align-items-center gap-2 border-start-md">
                        <span class="status-dot status-dot-animated {{ $waConnected ? 'bg-green' : 'bg-yellow' }}"></span>
                        <div>
                            <span class="text-dark fw-medium">WhatsApp Gateway:</span> 
                            <span>{{ $waConnected ? 'Terhubung (' . $waType . ')' : 'Belum Terhubung' }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 d-flex align-items-center gap-2 border-start-md">
                        <span class="status-dot {{ $autocutConfig['enabled'] ? 'bg-green' : 'bg-secondary' }}"></span>
                        <div>
                            <span class="text-dark fw-medium">Auto-Cut Isolir:</span> 
                            <span>{{ $autocutConfig['enabled'] ? 'Aktif (Grace ' . $autocutConfig['grace_period'] . ' Hari)' : 'Nonaktif' }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 d-flex align-items-center justify-content-md-end gap-2 border-start-md">
                        @if($pendingManualPayments > 0)
                            <a href="{{ route('tenant.manual-payments.index') }}" class="badge bg-yellow-lt text-yellow text-decoration-none">
                                <i class="ti ti-alert-circle me-1"></i> {{ $pendingManualPayments }} Transfer Perlu Verifikasi
                            </a>
                        @else
                            <span class="text-muted">
                                <i class="ti ti-check me-1"></i> Semua Transfer Terverifikasi
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Primary Metric Cards (Soft & Cohesive - 2x2 Grid on Mobile) -->
        <div class="row row-deck row-cards mb-4">
            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-2 p-md-3">
                        <div class="d-flex align-items-center">
                            <div class="subheader text-secondary text-truncate">Pelanggan Aktif</div>
                            <div class="ms-auto lh-1 d-none d-sm-block">
                                <span class="avatar avatar-sm bg-blue-lt text-blue rounded">
                                    <i class="ti ti-users fs-2"></i>
                                </span>
                            </div>
                        </div>
                        <div class="fs-1 fw-bold text-dark mt-1 mt-md-2 mb-1 metric-value-mobile">{{ number_format($activeCustomers) }}</div>
                        <div class="text-secondary small d-none d-sm-block">
                            Dari total <strong>{{ number_format($totalCustomers) }}</strong> terdaftar
                        </div>
                        <div class="text-secondary small d-sm-none" style="font-size: 0.72rem;">
                            Total: {{ number_format($totalCustomers) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-2 p-md-3">
                        <div class="d-flex align-items-center">
                            <div class="subheader text-secondary text-truncate">Tagihan Menunggak</div>
                            <div class="ms-auto lh-1 d-none d-sm-block">
                                <span class="avatar avatar-sm bg-yellow-lt text-yellow rounded">
                                    <i class="ti ti-clock-pause fs-2"></i>
                                </span>
                            </div>
                        </div>
                        <div class="fs-1 fw-bold text-dark mt-1 mt-md-2 mb-1 metric-value-mobile">{{ number_format($unpaidCustomers) }} <span class="fs-4 fw-normal text-secondary d-none d-sm-inline">User</span></div>
                        <div class="text-secondary small d-none d-sm-block">
                            Piutang: <strong>Rp {{ number_format($receivables, 0, ',', '.') }}</strong>
                        </div>
                        <div class="text-secondary small d-sm-none" style="font-size: 0.72rem;">
                            Rp {{ number_format($receivables / 1000, 0) }}k
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-2 p-md-3">
                        <div class="d-flex align-items-center">
                            <div class="subheader text-secondary text-truncate">Terisolir</div>
                            <div class="ms-auto lh-1 d-none d-sm-block">
                                <span class="avatar avatar-sm bg-red-lt text-red rounded">
                                    <i class="ti ti-wifi-off fs-2"></i>
                                </span>
                            </div>
                        </div>
                        <div class="fs-1 fw-bold text-dark mt-1 mt-md-2 mb-1 metric-value-mobile">{{ number_format($isolatedCustomers) }} <span class="fs-4 fw-normal text-secondary d-none d-sm-inline">User</span></div>
                        <div class="text-secondary small d-none d-sm-block">
                            Akses internet dibatasi otomatis
                        </div>
                        <div class="text-secondary small d-sm-none" style="font-size: 0.72rem;">
                            Auto-Cut
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card">
                    <div class="card-body p-2 p-md-3">
                        <div class="d-flex align-items-center">
                            <div class="subheader text-secondary text-truncate">Pendapatan Bulan Ini</div>
                            <div class="ms-auto lh-1 d-none d-sm-block">
                                <span class="avatar avatar-sm bg-green-lt text-green rounded">
                                    <i class="ti ti-wallet fs-2"></i>
                                </span>
                            </div>
                        </div>
                        <div class="fs-1 fw-bold text-dark mt-1 mt-md-2 mb-1 metric-value-mobile">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</div>
                        <div class="text-secondary small d-none d-sm-block">
                            Periode berjalan: <strong>{{ now()->translatedFormat('F Y') }}</strong>
                        </div>
                        <div class="text-secondary small d-sm-none" style="font-size: 0.72rem;">
                            {{ now()->translatedFormat('M Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6-Month Billing & Cashflow Trend Chart -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="card-title mb-0">Tren Tagihan & Pendapatan (6 Bulan Terakhir)</h3>
                    <div class="text-secondary small mt-1">Perbandingan nilai invoice diterbitkan terhadap pembayaran yang berhasil diterima</div>
                </div>
                <div class="d-flex align-items-center gap-3 small">
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge bg-blue" style="width: 10px; height: 10px; padding: 0; border-radius: 2px;"></span>
                        <span class="text-secondary">Diterbitkan</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span class="badge bg-green" style="width: 10px; height: 10px; padding: 0; border-radius: 2px;"></span>
                        <span class="text-secondary">Diterima (Lunas)</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div style="height: 180px;" class="d-flex align-items-end justify-content-between gap-3 pt-3">
                    @php
                        $maxVal = max(1, max($chartInvoiced), max($chartRevenue));
                    @endphp
                    @foreach($chartMonths as $idx => $mName)
                        @php
                            $invHeight = min(100, round(($chartInvoiced[$idx] / $maxVal) * 100));
                            $revHeight = min(100, round(($chartRevenue[$idx] / $maxVal) * 100));
                        @endphp
                        <div class="d-flex flex-column align-items-center flex-fill h-100 justify-content-end">
                            <div class="d-flex align-items-end gap-1 w-100 justify-content-center" style="height: 140px;">
                                <!-- Invoiced Bar -->
                                <div class="bg-blue-lt rounded-top" style="width: 22px; height: {{ max(4, $invHeight) }}%;" title="Tagihan: Rp {{ number_format($chartInvoiced[$idx], 0, ',', '.') }}"></div>
                                <!-- Revenue Bar -->
                                <div class="bg-green rounded-top" style="width: 22px; height: {{ max(4, $revHeight) }}%;" title="Lunas: Rp {{ number_format($chartRevenue[$idx], 0, ',', '.') }}"></div>
                            </div>
                            <div class="text-secondary text-center small mt-2 fw-medium" style="font-size: 0.75rem;">
                                {{ $mName }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Section 1: Tagihan & Pembayaran (Spacious Invoices & Cashflow) -->
        <div class="row row-cards mb-4">
            <!-- Tagihan Terbaru (Lebar & Leluasa) -->
            <div class="col-12 col-xl-7">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="card-title mb-0">Daftar Tagihan Terbaru</h3>
                            <div class="text-secondary small">Tagihan yang baru saja diterbitkan ke pelanggan</div>
                        </div>
                        <a href="{{ route('tenant.invoices.index') }}" class="btn btn-sm btn-ghost-secondary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Nomor Tagihan</th>
                                    <th>Pelanggan</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoices as $inv)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tenant.invoices.show', $inv->id) }}" class="fw-bold text-decoration-none font-monospace">
                                                {{ $inv->invoice_number }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $inv->customer->name ?? '-' }}</div>
                                            <div class="text-secondary small">{{ $inv->customer->phone ?? '' }}</div>
                                        </td>
                                        <td class="text-secondary small">{{ $inv->due_date->format('d/m/Y') }}</td>
                                        <td class="fw-semibold text-dark">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <x-badge :status="$inv->status" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-secondary">
                                            Belum ada tagihan yang diterbitkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Terakhir Berhasil Diterima -->
            <div class="col-12 col-xl-5">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="card-title mb-0">Pembayaran Terakhir</h3>
                            <div class="text-secondary small">Arus kas masuk yang telah lunas</div>
                        </div>
                        <a href="{{ route('tenant.invoices.payments') }}" class="btn btn-sm btn-ghost-secondary">
                            Semua
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Metode</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayments as $pay)
                                    <tr>
                                        <td class="text-secondary small">{{ $pay->paid_at ? \Carbon\Carbon::parse($pay->paid_at)->format('d/m/Y H:i') : '-' }}</td>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $pay->customer->name ?? '-' }}</div>
                                            <div class="text-secondary small font-monospace">{{ $pay->invoice?->invoice_number ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-lt text-secondary">{{ $pay->paymentMethod?->name ?? 'Online / Gateway' }}</span>
                                        </td>
                                        <td class="fw-semibold text-green">Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-secondary">
                                            Belum ada transaksi pembayaran lunas bulan ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Jaringan MikroTik & Akses Cepat Operasional -->
        <div class="row row-cards">
            <!-- Status Router MikroTik -->
            <div class="col-12 col-xl-7">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="card-title mb-0">Status Router MikroTik</h3>
                            <div class="text-secondary small">Router gateway dan BRAS yang terhubung ke sistem</div>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('tenant.routers.create') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-plus me-1"></i> Tambah Router
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Nama Router</th>
                                    <th>Host / IP Tunnel</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($routers as $router)
                                    <tr>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $router->name }}</div>
                                        </td>
                                        <td>
                                            <span class="font-monospace text-secondary small">{{ $router->host ?? $router->tunnel_ip ?? '10.99.1.x' }}</span>
                                        </td>
                                        <td>
                                            <x-badge :status="$router->connection_type" />
                                        </td>
                                        <td>
                                            <x-badge :status="$router->status" :dot="true" />
                                        </td>
                                        <td>
                                            <a href="{{ route('tenant.routers.index') }}" class="btn btn-sm btn-ghost-secondary">Kelola</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-secondary">
                                            Belum ada router yang terhubung.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Akses Cepat Operasional -->
            <div class="col-12 col-xl-5">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Akses Cepat Operasional</h3>
                        <div class="text-secondary small">Pintasan navigasi tugas penting</div>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('tenant.customers.create') }}" class="list-group-item list-group-item-action d-flex align-items-center py-2 px-3">
                            <span class="avatar avatar-xs bg-blue-lt text-blue me-3 rounded">
                                <i class="ti ti-user-plus"></i>
                            </span>
                            <div class="flex-fill">
                                <div class="fw-medium text-dark">Pasang Pelanggan Baru</div>
                                <div class="text-secondary small">Buat akun PPPoE & sinkronisasi MikroTik</div>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </a>

                        <a href="{{ route('tenant.invoices.create') }}" class="list-group-item list-group-item-action d-flex align-items-center py-2 px-3">
                            <span class="avatar avatar-xs bg-green-lt text-green me-3 rounded">
                                <i class="ti ti-file-invoice"></i>
                            </span>
                            <div class="flex-fill">
                                <div class="fw-medium text-dark">Terbitkan Tagihan Bulanan</div>
                                <div class="text-secondary small">Generate invoice & kirim notifikasi tagihan</div>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </a>

                        <a href="{{ route('tenant.manual-payments.index') }}" class="list-group-item list-group-item-action d-flex align-items-center py-2 px-3">
                            <span class="avatar avatar-xs bg-yellow-lt text-yellow me-3 rounded">
                                <i class="ti ti-checkup-list"></i>
                            </span>
                            <div class="flex-fill">
                                <div class="fw-medium text-dark">Verifikasi Transfer Bank</div>
                                <div class="text-secondary small">{{ $pendingManualPayments }} transfer menunggu persetujuan</div>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </a>

                        <a href="{{ route('tenant.reports.index') }}" class="list-group-item list-group-item-action d-flex align-items-center py-2 px-3">
                            <span class="avatar avatar-xs bg-purple-lt text-purple me-3 rounded">
                                <i class="ti ti-chart-bar"></i>
                            </span>
                            <div class="flex-fill">
                                <div class="fw-medium text-dark">Laporan Pendapatan & Piutang</div>
                                <div class="text-secondary small">Rekapitulasi keuangan bulanan & arus kas</div>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
