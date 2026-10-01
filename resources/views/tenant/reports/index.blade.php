@extends('layouts.tenant')

@section('title', 'Laporan Operasional & Keuangan')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Analisis & Laporan</div>
                <h2 class="page-title">Pusat Laporan Bisnis RT/RW Net</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                    <i class="ti ti-printer me-1"></i> Cetak Halaman
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Top Horizontal Navigation Tabs (Section 8: Revenue, Receivables, Customers, Payments, Monthly Report) -->
        <div class="card mb-3">
            <div class="card-header border-bottom-0 pb-0">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a href="{{ route('tenant.reports.index', ['tab' => 'revenue']) }}" class="nav-link {{ $tab === 'revenue' ? 'active fw-bold' : '' }}">
                            <i class="ti ti-chart-line me-1"></i> Pendapatan (Revenue)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tenant.reports.index', ['tab' => 'receivables']) }}" class="nav-link {{ $tab === 'receivables' ? 'active fw-bold' : '' }}">
                            <i class="ti ti-alert-triangle me-1"></i> Piutang (Receivables)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tenant.reports.index', ['tab' => 'customers']) }}" class="nav-link {{ $tab === 'customers' ? 'active fw-bold' : '' }}">
                            <i class="ti ti-users me-1"></i> Pelanggan (Customers)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tenant.reports.index', ['tab' => 'payments']) }}" class="nav-link {{ $tab === 'payments' ? 'active fw-bold' : '' }}">
                            <i class="ti ti-credit-card me-1"></i> Metode Pembayaran (Payments)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tenant.reports.index', ['tab' => 'monthly']) }}" class="nav-link {{ $tab === 'monthly' ? 'active fw-bold' : '' }}">
                            <i class="ti ti-calendar-event me-1"></i> Laporan Bulanan (Monthly)
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        @if($tab === 'revenue')
            <!-- TAB 1: REVENUE -->
            <div class="row row-cards mb-4">
                <div class="col-md-4">
                    <x-stat-box 
                        label="Total Pendapatan Tahun {{ $year }}" 
                        value="Rp {{ number_format($totalRevenueYear, 0, ',', '.') }}" 
                        icon="ti ti-wallet" 
                        color="green"
                    />
                </div>
                <div class="col-md-4">
                    <x-stat-box 
                        label="Tagihan Berhasil Lunas" 
                        value="{{ number_format($paidInvoicesCount) }}" 
                        icon="ti ti-check" 
                        color="teal"
                    />
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ route('tenant.reports.index') }}" class="card card-body">
                        <input type="hidden" name="tab" value="revenue">
                        <label class="form-label small text-secondary">Pilih Tahun Pembukuan</label>
                        <div class="input-group">
                            <input type="number" name="year" class="form-control" value="{{ $year }}" min="2020" max="2035">
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Rincian Pendapatan Tiap Bulan (Tahun {{ $year }})</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th class="text-end">Total Dana Diterima</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $monthNames = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                            @endphp
                            @foreach($monthlyRevenue as $mIndex => $rev)
                                <tr>
                                    <td>{{ $monthNames[$mIndex] }} {{ $year }}</td>
                                    <td class="text-end fw-bold {{ $rev > 0 ? 'text-success' : 'text-secondary' }}">
                                        Rp {{ number_format($rev, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($tab === 'receivables')
            <!-- TAB 2: RECEIVABLES -->
            <div class="row row-cards mb-4">
                <div class="col-md-6">
                    <x-stat-box 
                        label="Total Piutang Belum Tertagih" 
                        value="Rp {{ number_format($totalReceivables, 0, ',', '.') }}" 
                        icon="ti ti-alert-circle" 
                        color="danger"
                    />
                </div>
                <div class="col-md-6">
                    <x-stat-box 
                        label="Jumlah Tagihan Belum Lunas" 
                        value="{{ number_format($unpaidInvoicesCount) }}" 
                        icon="ti ti-clock" 
                        color="yellow"
                    />
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Tagihan Menunggak & Lewat Jatuh Tempo</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>No. Tagihan</th>
                                <th>Pelanggan</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th class="text-end">Nominal Piutang</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($overdueInvoices as $inv)
                                <tr>
                                    <td class="fw-bold">{{ $inv->invoice_number }}</td>
                                    <td>
                                        <div>{{ $inv->customer->name ?? '-' }}</div>
                                        <div class="text-secondary small">{{ $inv->customer->phone ?? '' }}</div>
                                    </td>
                                    <td class="text-danger small font-monospace">
                                        {{ $inv->due_date->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <x-badge :status="$inv->status" :dot="true" />
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        Rp {{ number_format($inv->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('tenant.invoices.show', $inv->id) }}" class="btn btn-sm btn-outline-primary">
                                            Rincian
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <x-empty-state title="Nihil Piutang" subtitle="Tidak ada tagihan tertunggak saat ini. Arus kas lancar!" />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($overdueInvoices->hasPages())
                    <div class="card-footer d-flex align-items-center">
                        {{ $overdueInvoices->links() }}
                    </div>
                @endif
            </div>

        @elseif($tab === 'customers')
            <!-- TAB 3: CUSTOMERS -->
            <div class="row row-cards mb-4">
                <div class="col-md-3">
                    <x-stat-box label="Total Pelanggan Terdaftar" value="{{ $totalCustomers }}" icon="ti ti-users" color="blue" />
                </div>
                <div class="col-md-3">
                    <x-stat-box label="Pelanggan Aktif" value="{{ $activeCustomers }}" icon="ti ti-circle-check" color="green" />
                </div>
                <div class="col-md-3">
                    <x-stat-box label="Pelanggan Terisolir" value="{{ $isolatedCustomers }}" icon="ti ti-wifi-off" color="danger" />
                </div>
                <div class="col-md-3">
                    <x-stat-box label="Pelanggan Ditangguhkan" value="{{ $suspendedCustomers }}" icon="ti ti-player-pause" color="yellow" />
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sebaran Status Pelanggan RT/RW Net</h3>
                </div>
                <div class="card-body">
                    <div class="progress progress-separated mb-3" style="height: 18px;">
                        @php
                            $pActive = $totalCustomers > 0 ? ($activeCustomers / $totalCustomers) * 100 : 0;
                            $pIsolated = $totalCustomers > 0 ? ($isolatedCustomers / $totalCustomers) * 100 : 0;
                            $pSuspended = $totalCustomers > 0 ? ($suspendedCustomers / $totalCustomers) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ $pActive }}%" title="Aktif: {{ number_format($pActive, 1) }}%"></div>
                        <div class="progress-bar bg-danger" style="width: {{ $pIsolated }}%" title="Terisolir: {{ number_format($pIsolated, 1) }}%"></div>
                        <div class="progress-bar bg-warning" style="width: {{ $pSuspended }}%" title="Suspended: {{ number_format($pSuspended, 1) }}%"></div>
                    </div>
                    <div class="row text-center">
                        <div class="col">
                            <span class="badge bg-success me-1"></span> Aktif: {{ $activeCustomers }} ({{ number_format($pActive, 1) }}%)
                        </div>
                        <div class="col">
                            <span class="badge bg-danger me-1"></span> Terisolir: {{ $isolatedCustomers }} ({{ number_format($pIsolated, 1) }}%)
                        </div>
                        <div class="col">
                            <span class="badge bg-warning me-1"></span> Suspended: {{ $suspendedCustomers }} ({{ number_format($pSuspended, 1) }}%)
                        </div>
                    </div>
                </div>
            </div>

        @elseif($tab === 'payments')
            <!-- TAB 4: PAYMENTS METHODS -->
            <div class="row row-cards mb-4">
                <div class="col-md-5">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title">Sebaran Per Kanal Pembayaran</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>Metode / Kanal</th>
                                        <th class="text-center">Transaksi</th>
                                        <th class="text-end">Total Dana</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($paymentMethodsStats as $pms)
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $pms->paymentMethod->name ?? 'Kasir / Tunai' }}</span>
                                            </td>
                                            <td class="text-center">{{ $pms->count }}x</td>
                                            <td class="text-end fw-bold text-success">
                                                Rp {{ number_format($pms->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-secondary py-3">Belum ada transaksi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Pembayaran Terbaru</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Tagihan & Pelanggan</th>
                                        <th>Metode</th>
                                        <th class="text-end">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPayments as $rp)
                                        <tr>
                                            <td class="text-secondary small font-monospace">{{ $rp->paid_at ? $rp->paid_at->format('d/m/Y H:i') : '-' }}</td>
                                            <td>
                                                <div class="fw-medium">{{ $rp->invoice->invoice_number ?? '-' }}</div>
                                                <div class="text-secondary small">{{ $rp->customer->name ?? '-' }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-blue-lt">
                                                    {{ $rp->paymentMethod->name ?? 'Kasir Manual' }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                Rp {{ number_format($rp->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-secondary py-3">Belum ada riwayat pembayaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($recentPayments->hasPages())
                            <div class="card-footer d-flex align-items-center">
                                {{ $recentPayments->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @elseif($tab === 'monthly')
            <!-- TAB 5: MONTHLY REPORT -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('tenant.reports.index') }}" class="row g-2 align-items-center">
                        <input type="hidden" name="tab" value="monthly">
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Pilih Bulan</label>
                            <select name="month" class="form-select">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ sprintf('%02d', $i) }}" {{ (int)$month === $i ? 'selected' : '' }}>
                                        Bulan {{ sprintf('%02d', $i) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-secondary">Pilih Tahun</label>
                            <input type="number" name="year" class="form-control" value="{{ $year }}" min="2020" max="2035">
                        </div>
                        <div class="col-md-4 pt-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-filter me-1"></i> Tampilkan Rekap Bulanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row row-cards mb-4">
                <div class="col-md-6">
                    <x-stat-box 
                        label="Total Nilai Tagihan Terbit" 
                        value="Rp {{ number_format($periodTotalBilled, 0, ',', '.') }}" 
                        icon="ti ti-receipt" 
                        color="blue"
                    />
                </div>
                <div class="col-md-6">
                    <x-stat-box 
                        label="Total Uang Masuk (Collected)" 
                        value="Rp {{ number_format($periodTotalCollected, 0, ',', '.') }}" 
                        icon="ti ti-cash" 
                        color="green"
                    />
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Tagihan Periode {{ $month }}/{{ $year }}</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>No. Tagihan</th>
                                <th>Pelanggan</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($selectedPeriodInvoices as $spi)
                                <tr>
                                    <td class="fw-bold">{{ $spi->invoice_number }}</td>
                                    <td>
                                        <div>{{ $spi->customer->name ?? '-' }}</div>
                                        <div class="text-secondary small">{{ $spi->customer->customer_code ?? '' }}</div>
                                    </td>
                                    <td class="font-monospace small">{{ $spi->due_date->format('d/m/Y') }}</td>
                                    <td>
                                        <x-badge :status="$spi->status" :dot="true" />
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp {{ number_format($spi->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <x-empty-state title="Belum Ada Tagihan" subtitle="Tidak ada tagihan yang diterbitkan pada bulan ini." />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($selectedPeriodInvoices->hasPages())
                    <div class="card-footer d-flex align-items-center">
                        {{ $selectedPeriodInvoices->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
