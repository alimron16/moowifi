@extends('layouts.super-admin')

@section('title', 'Dashboard Super Admin')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Ringkasan Platform MooWiFi</div>
                <h2 class="page-title">Dasbor Super Admin & Monitoring Platform</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Daftarkan Tenant Baru
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Row 1: Tenant & Customer Growth -->
        <div class="row row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="Total Tenant RT/RW Net" 
                    value="{{ number_format($totalTenants) }}" 
                    icon="ti ti-building" 
                    color="primary"
                    description="{{ $activeTenants }} Aktif, {{ $trialTenants }} Uji Coba, {{ $suspendedTenants }} Suspend"
                />
            </div>
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="Tenant Baru (Bulan Ini)" 
                    value="{{ number_format($newTenantsThisMonth) }}" 
                    icon="ti ti-user-plus" 
                    color="green"
                    description="Pertumbuhan bulan {{ now()->translatedFormat('F') }}"
                />
            </div>
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="Total Pelanggan Terdaftar" 
                    value="{{ number_format($totalCustomersPlatform) }}" 
                    icon="ti ti-users" 
                    color="purple"
                    description="Seluruh tenant se-Indonesia"
                />
            </div>
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="Status Kesehatan Sistem" 
                    value="{{ $systemHealth }}" 
                    icon="ti ti-heart-rate-monitor" 
                    color="{{ $systemHealth === 'OPTIMAL' ? 'teal' : 'yellow' }}"
                    description="{{ $failedWebhooks }} Gagal WH, {{ $routerErrors }} Router Offline"
                />
            </div>
        </div>

        <!-- Row 2: SaaS Revenue & Subscription Metrics (Section 7) -->
        <div class="row row-cards mb-4">
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="MRR (Monthly Recurring)" 
                    value="Rp {{ number_format($mrr, 0, ',', '.') }}" 
                    icon="ti ti-repeat" 
                    color="cyan"
                    description="Pendapatan berulang per bulan"
                />
            </div>
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="Pendapatan Langganan" 
                    value="Rp {{ number_format($subscriptionRevenue, 0, ',', '.') }}" 
                    icon="ti ti-coin" 
                    color="green"
                    description="Total omzet langganan aktif"
                />
            </div>
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="Langganan Kadaluarsa / Overdue" 
                    value="{{ number_format($overdueSubscriptions) }}" 
                    icon="ti ti-calendar-x" 
                    color="danger"
                    description="Perlu ditindaklanjuti untuk perpanjangan"
                />
            </div>
            <div class="col-sm-6 col-lg-3">
                <x-stat-box 
                    label="Notifikasi Gagal Dikirim" 
                    value="{{ number_format($failedNotifications) }}" 
                    icon="ti ti-mail-cancel" 
                    color="orange"
                    description="WhatsApp & Email gagal terkirim"
                />
            </div>
        </div>

        <div class="row row-cards">
            <!-- Recent Tenants -->
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Pendaftaran Tenant Terbaru</h3>
                        <div class="card-actions">
                            <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-sm btn-ghost-secondary">Kelola Semua</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Tenant & Kode</th>
                                    <th>Kontak Owner</th>
                                    <th>Status</th>
                                    <th>Terdaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTenants as $t)
                                    <tr>
                                        <td>
                                            <a href="{{ route('super-admin.tenants.show', $t->id) }}" class="fw-bold">
                                                {{ $t->name }}
                                            </a>
                                            <div class="text-secondary small">Kode: {{ $t->code }}</div>
                                        </td>
                                        <td>
                                            <div>{{ $t->email ?? '-' }}</div>
                                            <div class="text-secondary small">{{ $t->phone ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <x-badge :status="$t->status" :dot="true" />
                                        </td>
                                        <td>{{ $t->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-3">Belum ada tenant.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Webhooks -->
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Aktivitas Webhook Masuk</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Provider</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentWebhooks as $w)
                                    <tr>
                                        <td>
                                            <div class="small">{{ $w->created_at->format('H:i:s') }}</div>
                                            <div class="text-secondary small">{{ $w->ip_address }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-blue-lt">{{ $w->provider }}</span>
                                        </td>
                                        <td>
                                            <x-badge :status="$w->status" :dot="true" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-secondary py-3">Belum ada webhook callback.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
