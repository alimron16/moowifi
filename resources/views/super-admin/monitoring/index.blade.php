@extends('layouts.super-admin')

@section('title', 'System Monitoring & Diagnostik')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Observabilitas Platform</div>
                <h2 class="page-title">Monitoring Sistem & Diagnostik Platform</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-outline-primary" onclick="window.location.reload()">
                    <i class="ti ti-refresh me-1"></i> Segarkan Diagnostik
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Top Horizontal Navigation Tabs (Section 7: Routers, Queue, Scheduler, Webhook, System Health) -->
        <div class="card mb-3">
            <div class="card-header border-bottom-0 pb-0">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a href="{{ route('super-admin.monitoring.index', ['tab' => 'health']) }}" class="nav-link {{ $tab === 'health' ? 'active fw-bold' : '' }}">
                            <i class="ti ti-heart-rate-monitor me-1"></i> System Health
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('super-admin.monitoring.index', ['tab' => 'routers']) }}" class="nav-link {{ $tab === 'routers' ? 'active fw-bold' : '' }}">
                            <i class="ti ti-router me-1"></i> Routers ({{ $onlineRouters }}/{{ $totalRouters }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('super-admin.monitoring.index', ['tab' => 'queue']) }}" class="nav-link {{ $tab === 'queue' ? 'active fw-bold' : '' }}">
                            <i class="ti ti-stack-2 me-1"></i> Queue & Jobs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('super-admin.monitoring.index', ['tab' => 'scheduler']) }}" class="nav-link {{ $tab === 'scheduler' ? 'active fw-bold' : '' }}">
                            <i class="ti ti-clock-play me-1"></i> Scheduler (Cron)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('super-admin.monitoring.index', ['tab' => 'webhook']) }}" class="nav-link {{ $tab === 'webhook' ? 'active fw-bold' : '' }}">
                            <i class="ti ti-webhook me-1"></i> Webhook Gateway
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        @if($tab === 'health')
            <!-- TAB 1: SYSTEM HEALTH -->
            <div class="row row-cards mb-4">
                <div class="col-md-3">
                    <x-stat-box label="Server Status" value="ONLINE" icon="ti ti-server" color="green" description="PHP {{ phpversion() }} / Laravel 12" />
                </div>
                <div class="col-md-3">
                    <x-stat-box label="Database Health" value="CONNECTED" icon="ti ti-database" color="teal" description="MySQL Engine Operasional" />
                </div>
                <div class="col-md-3">
                    <x-stat-box label="Queue Jobs Antri" value="{{ $queueJobsCount }}" icon="ti ti-stack" color="blue" description="Menunggu eksekusi worker" />
                </div>
                <div class="col-md-3">
                    <x-stat-box label="Failed Jobs" value="{{ $failedJobsCount }}" icon="ti ti-alert-triangle" color="{{ $failedJobsCount > 0 ? 'danger' : 'green' }}" description="Pekerjaan latar belakang gagal" />
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Audit Log Aktivitas Terkini (Platform Wide)</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Event</th>
                                <th>Keterangan</th>
                                <th>Eksekutor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditLogs as $al)
                                <tr>
                                    <td class="text-secondary small font-monospace">{{ $al->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td><span class="badge bg-purple-lt">{{ $al->event }}</span></td>
                                    <td>{{ $al->description }}</td>
                                    <td class="text-secondary small">{{ $al->user->name ?? 'System Auto' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-secondary py-3">Belum ada audit log.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($tab === 'routers')
            <!-- TAB 2: ROUTERS HEALTH -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Monitoring Seluruh Router MikroTik Pelanggan Tenant</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Router</th>
                                <th>Tenant Pemilik</th>
                                <th>Metode Koneksi</th>
                                <th>Host / IP Tunnel</th>
                                <th>Status Konektivitas</th>
                                <th>Terakhir Dilihat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($routers as $r)
                                <tr>
                                    <td class="fw-bold">{{ $r->name }}</td>
                                    <td>
                                        <div>{{ $r->tenant->name ?? 'Tenant Terhapus' }}</div>
                                        <div class="text-secondary small">Kode: {{ $r->tenant->code ?? '-' }}</div>
                                    </td>
                                    <td><span class="badge bg-azure-lt">{{ $r->connection_type }}</span></td>
                                    <td class="font-monospace small">{{ $r->host ?? $r->tunnel_ip ?? '-' }}:{{ $r->port }}</td>
                                    <td><x-badge :status="$r->status" :dot="true" /></td>
                                    <td class="text-secondary small font-monospace">{{ $r->last_seen_at ? $r->last_seen_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-secondary py-3">Belum ada router terdaftar di platform.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($tab === 'queue')
            <!-- TAB 3: QUEUE -->
            <div class="row row-cards mb-4">
                <div class="col-md-6">
                    <x-stat-box label="Jobs Sedang Berjalan / Antri" value="{{ $queueJobsCount }}" icon="ti ti-player-play" color="blue" description="Driver: Database / Redis" />
                </div>
                <div class="col-md-6">
                    <x-stat-box label="Failed Jobs (Gagal Eksekusi)" value="{{ $failedJobsCount }}" icon="ti ti-alert-octagon" color="{{ $failedJobsCount > 0 ? 'danger' : 'green' }}" description="Pekerjaan error yang memerlukan retry" />
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Antrean Queue Worker</h3>
                </div>
                <div class="card-body">
                    <p class="text-secondary">
                        Queue worker memproses pengiriman notifikasi WhatsApp, pengiriman email invoice PDF via Gmail SMTP, pemanggilan Webhook callback, dan auto-cut isolir MikroTik di latar belakang secara asynchronous agar dashboard tetap cepat dan responsif.
                    </p>
                    <div class="bg-dark text-white p-3 rounded font-monospace small">
                        $ php artisan queue:work --tries=3 --timeout=90
                    </div>
                </div>
            </div>

        @elseif($tab === 'scheduler')
            <!-- TAB 4: SCHEDULER (CRON) -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jadwal Tugas Otomatis Platform (Laravel Cron Engine)</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Perintah (Artisan Command)</th>
                                <th>Jadwal Eksekusi</th>
                                <th>Interval</th>
                                <th>Fungsi Operasional</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scheduledTasks as $st)
                                <tr>
                                    <td class="font-monospace fw-bold text-primary">{{ $st['command'] }}</td>
                                    <td class="font-monospace small"><span class="badge bg-blue-lt">{{ $st['cron'] }}</span></td>
                                    <td>{{ $st['interval'] }}</td>
                                    <td>{{ $st['purpose'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($tab === 'webhook')
            <!-- TAB 5: WEBHOOK -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Log Masuk Webhook Payment Gateway (Platform Wide)</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Provider</th>
                                <th>IP Pengirim</th>
                                <th>No. Referensi Eksternal</th>
                                <th>Status Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($webhooks as $wh)
                                <tr>
                                    <td class="text-secondary small font-monospace">{{ $wh->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td><span class="badge bg-purple-lt">{{ $wh->provider }}</span></td>
                                    <td class="font-monospace small">{{ $wh->ip_address }}</td>
                                    <td class="font-monospace small">{{ $wh->external_reference ?? '-' }}</td>
                                    <td><x-badge :status="$wh->status" :dot="true" /></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-3">Belum ada webhook yang diterima.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($webhooks->hasPages())
                    <div class="card-footer d-flex align-items-center">
                        {{ $webhooks->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
