@extends('layouts.base')

@section('content')
<aside class="navbar navbar-vertical navbar-expand-lg navbar-light bg-white border-end" id="sidebar-main">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand py-2 my-1">
            <a href="{{ route('super-admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('logo.png') }}" alt="MooWiFi" class="rounded p-1 bg-white border" style="width: 36px; height: 36px; object-fit: contain;">
                <div class="text-start">
                    <div class="fs-3 fw-bold text-dark lh-1">MooWiFi</div>
                    <div class="small text-danger fw-semibold mt-1">Super Admin Panel</div>
                </div>
            </a>
        </h1>

        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('super-admin.dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-dashboard fs-2"></i>
                        </span>
                        <span class="nav-link-title">Dashboard</span>
                    </a>
                </li>

                <!-- Tenants (Section 7: All Tenants, Active, Trial, Suspended) -->
                <li class="nav-item dropdown {{ request()->is('super-admin/tenants*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#sidebar-sa-tenants" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('super-admin/tenants*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-building-community fs-2"></i>
                        </span>
                        <span class="nav-link-title">Kelola Tenant RT/RW Net</span>
                    </a>
                    <div class="dropdown-menu {{ request()->is('super-admin/tenants*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request()->routeIs('super-admin.tenants.index') && !request('status') ? 'active' : '' }}" href="{{ route('super-admin.tenants.index') }}">
                            Semua Tenant (All)
                        </a>
                        <a class="dropdown-item {{ request('status') === 'ACTIVE' ? 'active' : '' }}" href="{{ route('super-admin.tenants.index', ['status' => 'ACTIVE']) }}">
                            Aktif (Active)
                        </a>
                        <a class="dropdown-item {{ request('status') === 'TRIAL' ? 'active' : '' }}" href="{{ route('super-admin.tenants.index', ['status' => 'TRIAL']) }}">
                            Uji Coba (Trial)
                        </a>
                        <a class="dropdown-item {{ request('status') === 'SUSPENDED' ? 'active' : '' }}" href="{{ route('super-admin.tenants.index', ['status' => 'SUSPENDED']) }}">
                            Ditangguhkan (Suspended)
                        </a>
                    </div>
                </li>

                <!-- Paket Langganan -->
                <li class="nav-item {{ request()->is('super-admin/plans*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('super-admin.plans.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-layers-linked fs-2"></i>
                        </span>
                        <span class="nav-link-title">Paket Langganan</span>
                    </a>
                </li>

                <!-- Subscriptions -->
                <li class="nav-item {{ request()->is('super-admin/subscriptions*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('super-admin.subscriptions.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-badge-ad fs-2"></i>
                        </span>
                        <span class="nav-link-title">Langganan Tenant</span>
                    </a>
                </li>

                <!-- Pembayaran Langganan -->
                <li class="nav-item {{ request()->is('super-admin/payments*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('super-admin.payments.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-cash fs-2"></i>
                        </span>
                        <span class="nav-link-title">Pembayaran Langganan</span>
                    </a>
                </li>

                <!-- System Monitoring (Section 7: Routers, Queue, Scheduler, Webhook, System Health) -->
                <li class="nav-item dropdown {{ request()->is('super-admin/monitoring*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#sidebar-sa-mon" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('super-admin/monitoring*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-heart-rate-monitor fs-2"></i>
                        </span>
                        <span class="nav-link-title">System Monitoring</span>
                    </a>
                    <div class="dropdown-menu {{ request()->is('super-admin/monitoring*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request('tab') === 'health' || (!request('tab') && request()->routeIs('super-admin.monitoring.index')) ? 'active' : '' }}" href="{{ route('super-admin.monitoring.index', ['tab' => 'health']) }}">
                            System Health
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'routers' ? 'active' : '' }}" href="{{ route('super-admin.monitoring.index', ['tab' => 'routers']) }}">
                            Routers Health
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'queue' ? 'active' : '' }}" href="{{ route('super-admin.monitoring.index', ['tab' => 'queue']) }}">
                            Queue Jobs
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'scheduler' ? 'active' : '' }}" href="{{ route('super-admin.monitoring.index', ['tab' => 'scheduler']) }}">
                            Scheduler (Cron)
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'webhook' ? 'active' : '' }}" href="{{ route('super-admin.monitoring.index', ['tab' => 'webhook']) }}">
                            Webhook Gateway
                        </a>
                    </div>
                </li>

                <!-- Payment Providers -->
                <li class="nav-item {{ request()->is('super-admin/payment-providers*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('super-admin.payment-providers.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-credit-card fs-2"></i>
                        </span>
                        <span class="nav-link-title">Payment Providers</span>
                    </a>
                </li>

                <!-- WhatsApp -->
                <li class="nav-item {{ request()->is('super-admin/whatsapp*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('super-admin.whatsapp.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-brand-whatsapp fs-2"></i>
                        </span>
                        <span class="nav-link-title">WhatsApp System</span>
                    </a>
                </li>

                <!-- Email -->
                <li class="nav-item {{ request()->is('super-admin/email*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('super-admin.email.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-mail fs-2"></i>
                        </span>
                        <span class="nav-link-title">Email System</span>
                    </a>
                </li>

                <!-- System Logs -->
                <li class="nav-item {{ request()->is('super-admin/logs*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('super-admin.logs.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-file-text fs-2"></i>
                        </span>
                        <span class="nav-link-title">System Logs</span>
                    </a>
                </li>

                <!-- Announcements -->
                <li class="nav-item {{ request()->is('super-admin/announcements*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('super-admin.announcements.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-speakerphone fs-2"></i>
                        </span>
                        <span class="nav-link-title">Announcements</span>
                    </a>
                </li>

                <!-- Support / Tickets -->
                <li class="nav-item {{ request()->is('super-admin/support*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('super-admin.support.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-lifebuoy fs-2"></i>
                        </span>
                        <span class="nav-link-title">Support / Tickets</span>
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-item {{ request()->is('super-admin/settings*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('super-admin.settings.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-settings fs-2"></i>
                        </span>
                        <span class="nav-link-title">Platform Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>

<header class="navbar navbar-light d-none d-lg-flex d-print-none border-bottom bg-white">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger-lt fw-bold">SUPER ADMIN MODE</span>
            <span class="text-secondary small d-none d-md-inline">MooWiFi Master Control Console</span>
        </div>

        <div class="navbar-nav flex-row ms-auto align-items-center gap-3">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Buka profil Super Admin">
                    <span class="avatar avatar-sm bg-danger text-white font-weight-bold">SA</span>
                    <div class="d-none d-xl-block ps-2 text-start">
                        <div class="fw-medium">{{ Auth::user()->name }}</div>
                        <div class="small text-secondary">Platform Owner</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('super-admin.settings.index') }}" class="dropdown-item">
                        <i class="ti ti-settings me-2"></i> Pengaturan Platform
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="ti ti-logout me-2"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="page-wrapper">
    <div class="container-xl mt-3">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="danger">{{ session('error') }}</x-alert>
        @endif
    </div>

    @yield('super-admin-content')
</div>
@endsection
