@extends('layouts.base')

@section('body-class', 'layout-fluid')

@section('content')
<!-- Sidebar Navigation -->
<aside class="navbar navbar-vertical navbar-expand-md navbar-light bg-white border-end" id="sidebar-main">
    <div class="container-fluid">
        <!-- Mobile Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Brand Logo / Name -->
        <h1 class="navbar-brand py-2 my-1">
            <a href="{{ route('tenant.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('logo.png') }}" alt="MooWiFi Logo" class="rounded p-1 bg-white border" style="width: 36px; height: 36px; object-fit: contain;">
                <div class="text-start">
                    <div class="fs-3 fw-bold text-dark lh-1">MooWiFi</div>
                    <div class="small text-secondary fw-normal mt-1 text-truncate" style="max-width: 180px;">{{ Auth::user()->tenant?->name ?? 'Tenant' }}</div>
                </div>
            </a>
        </h1>

        <!-- Mobile User Avatar -->
        <div class="navbar-nav flex-row d-md-none">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown">
                    <span class="avatar avatar-sm bg-primary-lt text-primary">{{ substr(Auth::user()->name, 0, 2) }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="{{ route('tenant.settings.index') }}" class="dropdown-item">Pengaturan</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">Keluar</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation Menu Items -->
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('tenant.dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-dashboard fs-2"></i>
                        </span>
                        <span class="nav-link-title">Dashboard</span>
                    </a>
                </li>

                <!-- Customers (Section 8: All Customers, Active, Overdue, Isolated, Suspended, Add Customer) -->
                @if(Auth::user()->canAccessModule('customers'))
                <li class="nav-item dropdown {{ request()->is('customers*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#sidebar-customers" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('customers*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-users fs-2"></i>
                        </span>
                        <span class="nav-link-title">Pelanggan</span>
                    </a>
                    <div class="dropdown-menu {{ request()->is('customers*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request()->routeIs('tenant.customers.index') && !request('status') ? 'active' : '' }}" href="{{ route('tenant.customers.index') }}">
                            Semua Pelanggan
                        </a>
                        <a class="dropdown-item {{ request('status') === 'ACTIVE' ? 'active' : '' }}" href="{{ route('tenant.customers.index', ['status' => 'ACTIVE']) }}">
                            Pelanggan Aktif
                        </a>
                        <a class="dropdown-item {{ request('status') === 'OVERDUE' ? 'active' : '' }}" href="{{ route('tenant.customers.index', ['status' => 'OVERDUE']) }}">
                            Menunggak (Overdue)
                        </a>
                        <a class="dropdown-item {{ request('status') === 'ISOLATED' ? 'active' : '' }}" href="{{ route('tenant.customers.index', ['status' => 'ISOLATED']) }}">
                            Terisolir (Auto-Cut)
                        </a>
                        <a class="dropdown-item {{ request('status') === 'SUSPENDED' ? 'active' : '' }}" href="{{ route('tenant.customers.index', ['status' => 'SUSPENDED']) }}">
                            Ditangguhkan (Suspended)
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('tenant.customers.create') ? 'active' : '' }}" href="{{ route('tenant.customers.create') }}">
                            Tambah Pelanggan Baru
                        </a>
                    </div>
                </li>
                @endif

                <!-- Billing (Section 8: Invoices, Generate Invoice, Due Today, Overdue, Payments, Payment Verification) -->
                @if(Auth::user()->canAccessModule('billing') || Auth::user()->canAccessModule('invoices'))
                <li class="nav-item dropdown {{ request()->is('invoices*') || request()->is('billing*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#sidebar-billing" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('invoices*') || request()->is('billing*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-receipt fs-2"></i>
                        </span>
                        <span class="nav-link-title">Tagihan & Billing</span>
                    </a>
                    <div class="dropdown-menu {{ request()->is('invoices*') || request()->is('billing*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request()->routeIs('tenant.invoices.index') && !request('status') && !request('due_today') ? 'active' : '' }}" href="{{ route('tenant.invoices.index') }}">
                            Semua Tagihan (Invoices)
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('tenant.invoices.create') ? 'active' : '' }}" href="{{ route('tenant.invoices.create') }}">
                            Terbitkan Tagihan Baru
                        </a>
                        <a class="dropdown-item {{ request('due_today') ? 'active' : '' }}" href="{{ route('tenant.invoices.index', ['due_today' => 1]) }}">
                            Jatuh Tempo Hari Ini
                        </a>
                        <a class="dropdown-item {{ request('status') === 'OVERDUE' ? 'active' : '' }}" href="{{ route('tenant.invoices.index', ['status' => 'OVERDUE']) }}">
                            Lewat Jatuh Tempo (Overdue)
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('tenant.invoices.payments') ? 'active' : '' }}" href="{{ route('tenant.invoices.payments') }}">
                            Riwayat Pembayaran
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('tenant.manual-payments.index') ? 'active' : '' }}" href="{{ route('tenant.manual-payments.index') }}">
                            Verifikasi Transfer Manual
                        </a>
                    </div>
                </li>
                @endif

                <!-- Internet Packages -->
                @if(Auth::user()->canAccessModule('packages'))
                <li class="nav-item {{ request()->routeIs('tenant.packages.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('tenant.packages.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-package fs-2"></i>
                        </span>
                        <span class="nav-link-title">Paket Internet</span>
                    </a>
                </li>
                @endif

                <!-- MikroTik (Section 8: Routers, Profiles, Online Users, Isolation, Logs) -->
                @if(Auth::user()->canAccessModule('routers'))
                <li class="nav-item dropdown {{ request()->is('routers*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#sidebar-mikrotik" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('routers*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-router fs-2"></i>
                        </span>
                        <span class="nav-link-title">MikroTik</span>
                    </a>
                    <div class="dropdown-menu {{ request()->is('routers*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request()->routeIs('tenant.routers.index') ? 'active' : '' }}" href="{{ route('tenant.routers.index') }}">
                            Daftar Router
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('tenant.routers.profiles') ? 'active' : '' }}" href="{{ route('tenant.routers.profiles') }}">
                            Profil Bandwidth (Profiles)
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('tenant.routers.online-users') ? 'active' : '' }}" href="{{ route('tenant.routers.online-users') }}">
                            Pengguna Online (PPPoE/Hotspot)
                        </a>
                        <a class="dropdown-item" href="{{ route('tenant.customers.index', ['status' => 'ISOLATED']) }}">
                            Pelanggan Terisolir (Auto-Cut)
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('tenant.routers.logs') ? 'active' : '' }}" href="{{ route('tenant.routers.logs') }}">
                            Log Aktivitas MikroTik
                        </a>
                    </div>
                </li>
                @endif

                <!-- Payments (Section 8: Payment Methods, Payment Gateway, Manual Payment, Transaction Logs) -->
                @if(Auth::user()->canAccessModule('payments'))
                <li class="nav-item dropdown {{ request()->is('payments*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#sidebar-payments" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('payments*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-credit-card fs-2"></i>
                        </span>
                        <span class="nav-link-title">Pembayaran</span>
                    </a>
                    <div class="dropdown-menu {{ request()->is('payments*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request()->routeIs('tenant.payment-methods.index') ? 'active' : '' }}" href="{{ route('tenant.payment-methods.index') }}">
                            Metode Pembayaran (Bank / QRIS)
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('tenant.payment-methods.gateway') ? 'active' : '' }}" href="{{ route('tenant.payment-methods.gateway') }}">
                            Payment Gateway (Duitku/Midtrans/Xendit/Tripay)
                        </a>
                        <a class="dropdown-item" href="{{ route('tenant.manual-payments.index') }}">
                            Verifikasi Transfer Manual
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('tenant.payment-methods.transactions') ? 'active' : '' }}" href="{{ route('tenant.payment-methods.transactions') }}">
                            Log Transaksi Gateway
                        </a>
                    </div>
                </li>
                @endif

                <!-- Notifications (Section 8: WhatsApp, Email, Templates, Logs) -->
                @if(Auth::user()->canAccessModule('notifications'))
                <li class="nav-item dropdown {{ request()->is('notifications*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#sidebar-notif" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('notifications*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-brand-whatsapp fs-2"></i>
                        </span>
                        <span class="nav-link-title">Notifikasi</span>
                    </a>
                    <div class="dropdown-menu {{ request()->is('notifications*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request()->routeIs('tenant.notifications.index') ? 'active' : '' }}" href="{{ route('tenant.notifications.index') }}">
                            WhatsApp Gateway (API & QR Scan)
                        </a>
                        <a class="dropdown-item" href="{{ route('tenant.settings.index', ['tab' => 'email']) }}">
                            Email Invoice (Gmail SMTP)
                        </a>
                        <a class="dropdown-item" href="{{ route('tenant.settings.index', ['tab' => 'templates']) }}">
                            Template Notifikasi
                        </a>
                        <a class="dropdown-item {{ request()->routeIs('tenant.notifications.logs') ? 'active' : '' }}" href="{{ route('tenant.notifications.logs') }}">
                            Log Notifikasi Terkirim
                        </a>
                    </div>
                </li>
                @endif

                <!-- Reports (Section 8: Revenue, Receivables, Customers, Payments, Monthly Report) -->
                @if(Auth::user()->canAccessModule('reports'))
                <li class="nav-item dropdown {{ request()->routeIs('tenant.reports.*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#sidebar-reports" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->routeIs('tenant.reports.*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-report-money fs-2"></i>
                        </span>
                        <span class="nav-link-title">Laporan</span>
                    </a>
                    <div class="dropdown-menu {{ request()->routeIs('tenant.reports.*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request('tab') === 'revenue' || (!request('tab') && request()->routeIs('tenant.reports.index')) ? 'active' : '' }}" href="{{ route('tenant.reports.index', ['tab' => 'revenue']) }}">
                            Pendapatan (Revenue)
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'receivables' ? 'active' : '' }}" href="{{ route('tenant.reports.index', ['tab' => 'receivables']) }}">
                            Piutang Tertunggak (Receivables)
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'customers' ? 'active' : '' }}" href="{{ route('tenant.reports.index', ['tab' => 'customers']) }}">
                            Pertumbuhan Pelanggan (Customers)
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'payments' ? 'active' : '' }}" href="{{ route('tenant.reports.index', ['tab' => 'payments']) }}">
                            Metode Pembayaran (Payments)
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'monthly' ? 'active' : '' }}" href="{{ route('tenant.reports.index', ['tab' => 'monthly']) }}">
                            Laporan Bulanan (Monthly Report)
                        </a>
                    </div>
                </li>
                @endif

                <!-- Settings (Section 8: Business Profile, Billing, Auto Cut, Payment, WhatsApp, Email, Users & Roles) -->
                @if(Auth::user()->isOwner())
                <li class="nav-item dropdown {{ request()->is('settings*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#sidebar-settings" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('settings*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-settings fs-2"></i>
                        </span>
                        <span class="nav-link-title">Pengaturan</span>
                    </a>
                    <div class="dropdown-menu {{ request()->is('settings*') ? 'show' : '' }}">
                        <a class="dropdown-item {{ request('tab') === 'business' ? 'active' : '' }}" href="{{ route('tenant.settings.index', ['tab' => 'business']) }}">
                            Profil Bisnis
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'billing' ? 'active' : '' }}" href="{{ route('tenant.settings.index', ['tab' => 'billing']) }}">
                            Billing & Jatuh Tempo
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'billing' ? 'active' : '' }}" href="{{ route('tenant.settings.index', ['tab' => 'billing']) }}">
                            Kebijakan Auto-Cut
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'payment' ? 'active' : '' }}" href="{{ route('tenant.settings.index', ['tab' => 'payment']) }}">
                            Payment Gateway
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'whatsapp' ? 'active' : '' }}" href="{{ route('tenant.settings.index', ['tab' => 'whatsapp']) }}">
                            WhatsApp Gateway
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'email' ? 'active' : '' }}" href="{{ route('tenant.settings.index', ['tab' => 'email']) }}">
                            Akun Gmail SMTP
                        </a>
                        <a class="dropdown-item {{ request('tab') === 'users' ? 'active' : '' }}" href="{{ route('tenant.settings.index', ['tab' => 'users']) }}">
                            Staf, Petugas & Hak Akses
                        </a>
                    </div>
                </li>
                @endif
            </ul>
        </div>
    </div>
</aside>

<!-- Topbar Header -->
<header class="navbar navbar-light d-none d-md-flex d-print-none border-bottom bg-white">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-icon btn-ghost-secondary border-0 me-1" id="btn-toggle-sidebar" aria-label="Toggle Sidebar" title="Buka/Tutup Sidebar">
                <i class="ti ti-layout-sidebar-left-collapse fs-2" id="icon-toggle-sidebar"></i>
            </button>
            <span class="text-secondary small d-none d-lg-inline">Wilayah Layanan:</span>
            <strong class="text-dark small">{{ Auth::user()->tenant?->name }}</strong>
        </div>

        <div class="navbar-nav flex-row order-md-last align-items-center gap-2">
            <!-- Theme Toggle Button -->
            <button type="button" class="btn btn-icon btn-ghost-secondary border-0" id="btn-toggle-theme" aria-label="Toggle Dark Mode" title="Ganti Mode Gelap / Terang">
                <i class="ti ti-moon fs-2" id="icon-toggle-theme"></i>
            </button>

            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Buka menu user">
                    <span class="avatar avatar-sm bg-light text-dark font-weight-bold border">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </span>
                    <div class="d-none d-xl-block ps-2 text-start">
                        <div class="fw-medium text-dark small">{{ Auth::user()->name }}</div>
                        <div class="text-muted" style="font-size: 0.72rem;">{{ Auth::user()->role }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('tenant.settings.index') }}" class="dropdown-item">
                        <i class="ti ti-settings me-2"></i> Pusat Pengaturan
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

<!-- Main Page Content Wrapper -->
<div class="page-wrapper">
    <!-- Alerts & Feedback Container -->
    <div class="container-xl mt-3">
        @if(session('success'))
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="danger">
                {{ session('error') }}
            </x-alert>
        @endif

        @if(session('warning'))
            <x-alert type="warning">
                {{ session('warning') }}
            </x-alert>
        @endif

        @if(session('info'))
            <x-alert type="info">
                {{ session('info') }}
            </x-alert>
        @endif

        @if(isset($errors) && $errors->any())
            <x-alert type="danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif
    </div>

    @yield('tenant-content')

    <footer class="footer footer-transparent d-print-none mt-auto py-3">
        <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
                <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                    <ul class="list-inline list-inline-dots mb-0 text-secondary small">
                        <li class="list-inline-item"><strong>MooWiFi</strong> &mdash; Otomatisasi Jaringan, Maksimalkan Cuan.</li>
                        <li class="list-inline-item">Billing & Manajemen RT/RW Net</li>
                        <li class="list-inline-item">&copy; {{ date('Y') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Sidebar Toggle
        const toggleBtn = document.getElementById('btn-toggle-sidebar');
        const iconToggle = document.getElementById('icon-toggle-sidebar');
        
        if (localStorage.getItem('mwifi_sidebar_collapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
            if (iconToggle) {
                iconToggle.className = 'ti ti-layout-sidebar-left-expand fs-2';
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                document.body.classList.toggle('sidebar-collapsed');
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                localStorage.setItem('mwifi_sidebar_collapsed', isCollapsed ? 'true' : 'false');
                if (iconToggle) {
                    iconToggle.className = isCollapsed 
                        ? 'ti ti-layout-sidebar-left-expand fs-2' 
                        : 'ti ti-layout-sidebar-left-collapse fs-2';
                }
            });
        }

        // 2. Dark / Light Theme Toggle
        const themeBtn = document.getElementById('btn-toggle-theme');
        const themeIcon = document.getElementById('icon-toggle-theme');

        function applyTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            document.body.setAttribute('data-bs-theme', theme);
            if (themeIcon) {
                themeIcon.className = theme === 'dark' ? 'ti ti-sun fs-2' : 'ti ti-moon fs-2';
            }
        }

        const currentTheme = localStorage.getItem('mwifi_theme') || 'light';
        applyTheme(currentTheme);

        if (themeBtn) {
            themeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const activeTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
                const nextTheme = activeTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('mwifi_theme', nextTheme);
                applyTheme(nextTheme);
            });
        }
    });
</script>
@endsection
