<!DOCTYPE html>
<html lang="id" style="scroll-behavior: smooth; scroll-padding-top: 80px;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MooWiFi | Platform SaaS Billing &amp; Manajemen RT/RW Net Terintegrasi MikroTik</title>
    <meta name="description" content="Aplikasi billing dan manajemen RT/RW Net &amp; ISP modern. Otomasi MikroTik Auto-Cut &amp; Auto-Restore, invoice bulanan otomatis via WhatsApp, serta integrasi multi payment gateway QRIS &amp; Virtual Account.">
    
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/png">
    
    <!-- Google Fonts: IBM Plex Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons Webfont & Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.36.0/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ============================================================
           MEDICLOUD DESIGN SYSTEM & TOKENS (MOONBYTE INSPIRED)
        ============================================================ */
        :root {
            --bs-primary: #FF8D6D;
            --bs-primary-rgb: 255, 141, 109;
            --bs-secondary: #003A43;
            --bs-secondary-rgb: 0, 58, 67;
            --bs-success: #006E2F;
            --bs-info: #00B8DB;
            --bs-warning: #F0B100;
            --bs-danger: #FB2C36;
            --bs-light: #f1f3f3;
            --bs-dark: #161b1d;
            --bs-body-font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --bs-body-color: #394447;
            --bs-body-bg: #ffffff;
            --bs-border-radius: 16px;
            
            --mc-orange: #FF8D6D;
            --mc-dark-green: #003A43;
            --mc-gray-100: #f1f3f3;
            --mc-gray-200: #e3e7e8;
            --mc-gray-700: #394447;
            --mc-gray-800: #22292b;
            --mc-gray-900: #161b1d;
        }

        body {
            font-family: var(--bs-body-font-family);
            color: var(--bs-body-color);
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            color: var(--mc-gray-900);
            font-weight: 600;
            letter-spacing: -0.02em;
        }

        .display-3 {
            font-size: calc(2rem + 2.2vw);
            font-weight: 700;
            line-height: 1.18;
            letter-spacing: -0.03em;
        }

        .display-6 {
            font-size: calc(1.5rem + 1.2vw);
            font-weight: 600;
            letter-spacing: -0.025em;
            color: var(--mc-gray-900);
        }

        /* Buttons (MediCloud 50px pill style) */
        .btn {
            border-radius: 50px;
            font-weight: 500;
            padding: 0.72rem 1.6rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.95rem;
        }

        .btn-primary {
            background-color: var(--mc-orange);
            border-color: var(--mc-orange);
            color: var(--mc-gray-900);
            font-weight: 600;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: #ff7b54 !important;
            border-color: #ff7b54 !important;
            color: var(--mc-gray-900) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 141, 109, 0.38);
        }

        .btn-outline-secondary {
            color: var(--mc-dark-green);
            border-color: var(--mc-dark-green);
            background: transparent;
        }

        .btn-outline-secondary:hover, .btn-outline-secondary:focus {
            background-color: rgba(0, 58, 67, 0.08);
            border-color: var(--mc-dark-green);
            color: var(--mc-dark-green);
            transform: translateY(-2px);
        }

        .btn-white {
            background-color: #ffffff;
            border-color: var(--mc-gray-200);
            color: var(--mc-gray-700);
        }

        .btn-white:hover {
            background-color: var(--mc-gray-100);
            color: var(--mc-gray-900);
            transform: translateY(-2px);
        }

        /* Surfaces & Colors */
        .bg-secondary {
            background-color: var(--mc-dark-green) !important;
        }

        .text-bg-secondary {
            background-color: var(--mc-dark-green) !important;
            color: #ffffff !important;
        }

        .text-bg-primary {
            background-color: var(--mc-orange) !important;
            color: var(--mc-gray-900) !important;
        }

        .bg-light {
            background-color: var(--mc-gray-100) !important;
        }

        .border-primary {
            border-color: var(--mc-orange) !important;
        }

        /* Hero & Section Spacing */
        .hero-section {
            padding-top: 4rem;
            padding-bottom: 5.5rem;
            background: radial-gradient(circle at 80% 20%, rgba(255, 141, 109, 0.12) 0%, rgba(255, 255, 255, 0) 50%);
        }

        @media (min-width: 992px) {
            .hero-section {
                padding-top: 5.5rem;
                padding-bottom: 7rem;
            }
        }

        .tech-badge-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid var(--mc-gray-200);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--mc-gray-800);
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }

        /* Stats Cards */
        .stat-card-mc {
            background: var(--mc-gray-100);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            border: 1px solid var(--mc-gray-200);
            transition: transform 0.2s;
        }

        .stat-card-mc:hover {
            transform: translateY(-3px);
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        }

        .stat-num-mc {
            font-size: 2.3rem;
            font-weight: 700;
            color: var(--mc-dark-green);
            line-height: 1.1;
            margin-bottom: 6px;
        }

        /* Feature Cards */
        .feature-card .card {
            border-radius: 16px;
            transition: all 0.25s ease;
        }

        .feature-card .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 36px rgba(0, 58, 67, 0.08);
            background: #ffffff !important;
        }

        .feature-icon-box {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background: rgba(0, 58, 67, 0.08);
            color: var(--mc-dark-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.25rem;
        }

        /* Pricing Cards */
        .pricing-card .card {
            border-radius: 20px;
            transition: all 0.25s ease;
        }

        .pricing-card .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.1);
        }

        /* Mockup Glass Card */
        .hero-mockup-card {
            background: #ffffff;
            border: 1px solid var(--mc-gray-200);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 58, 67, 0.12);
            overflow: hidden;
        }

        /* Ticket Tabs */
        .nav-pills-mc .nav-link {
            border-radius: 50px;
            padding: 8px 24px;
            font-weight: 600;
            color: var(--mc-dark-green);
            background: var(--mc-gray-100);
            border: 1px solid var(--mc-gray-200);
            margin: 0 4px;
        }

        .nav-pills-mc .nav-link.active {
            background: var(--mc-dark-green);
            color: #ffffff;
            border-color: var(--mc-dark-green);
        }
    </style>
</head>
<body>

    <!-- ============================================================
         NAVBAR
    ============================================================ -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-inline-flex gap-2 align-items-center lh-1" href="{{ url('/') }}">
                <img src="{{ asset('logo.png') }}" alt="MooWiFi Logo" class="img-fluid" style="height: 36px; border-radius: 8px;">
                <span style="font-weight: 700; font-size: 1.35rem; color: #003A43; letter-spacing: -0.02em;">MOO<span style="color: #FF8D6D;">WIFI</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="#features" class="nav-link fw-semibold text-dark px-3">Fitur</a></li>
                    <li class="nav-item"><a href="#howItWork" class="nav-link fw-semibold text-dark px-3">Cara Kerja</a></li>
                    <li class="nav-item"><a href="#pricing" class="nav-link fw-semibold text-dark px-3">Paket Harga</a></li>
                    <li class="nav-item"><a href="#support-ticket" class="nav-link fw-semibold text-dark px-3">Tiket Bantuan</a></li>
                    <li class="nav-item"><a href="#faq" class="nav-link fw-semibold text-dark px-3">FAQ</a></li>
                </ul>
                <div class="d-flex gap-2 align-items-center">
                    @auth
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('super-admin.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-dashboard me-1"></i> Panel Super Admin
                            </a>
                        @else
                            <a href="{{ route('tenant.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-dashboard me-1"></i> Dasbor Tenant
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-login me-1"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <span>Coba Gratis 14 Hari</span> <i class="ti ti-arrow-right fs-6"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- ============================================================
         HERO SECTION
    ============================================================ -->
    <section class="hero-section overflow-hidden">
        <div class="container">
            <div class="row align-items-center gy-5">
                <!-- Left Content -->
                <div class="col-lg-6 hero-left">
                    <span class="badge text-bg-secondary rounded-pill fw-normal d-inline-flex align-items-center py-2 px-3 mb-3">
                        <i class="ti ti-router me-2 text-warning"></i> <span>Platform Billing &amp; MikroTik RT/RW Net No. 1</span>
                    </span>

                    <h1 class="display-3 mb-4">
                        Otomatisasi Jaringan RT/RW Net, <span style="color: #FF8D6D;">Maksimalkan Cuan</span> Tanpa Pusing
                    </h1>

                    <p class="lead text-muted">
                        Kelola ratusan pelanggan internet, buat invoice bulanan otomatis via WhatsApp, terima pembayaran QRIS/VA otomatis, dan biarkan sistem melakukan <strong>Auto-Cut &amp; Auto-Restore MikroTik</strong> secara cerdas 24 jam nonstop.
                    </p>

                    <div class="mt-4 d-flex gap-3 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <span>Daftar Coba Gratis 14 Hari</span> <i class="fas fa-arrow-right fs-6"></i>
                        </a>
                        <a href="#support-ticket" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <i class="ti ti-ticket"></i> <span>Cek Status / Buat Tiket</span>
                        </a>
                    </div>

                    <div class="mt-4 d-flex align-items-center gap-3 text-secondary small">
                        <div><i class="fas fa-check-circle text-success me-1"></i> Tanpa Biaya Setup</div>
                        <div><i class="fas fa-check-circle text-success me-1"></i> Support Router CGNAT (IndiHome)</div>
                        <div><i class="fas fa-check-circle text-success me-1"></i> Verifikasi Email Aman</div>
                    </div>
                </div>

                <!-- Right Hero Mockup Card -->
                <div class="col-lg-6 hero-right">
                    <div class="hero-mockup-card p-4">
                        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-lt"><i class="fas fa-circle text-success me-1"></i> Router Utama Online</span>
                                <span class="badge bg-secondary-lt">RB4011 / CCR</span>
                            </div>
                            <span class="small text-muted font-monospace">Latency: 3ms</span>
                        </div>

                        <!-- Mini Dashboard Highlights -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="small text-muted">Invoice Bulan Ini</div>
                                    <div class="fs-4 fw-bold text-dark">485 Tagihan</div>
                                    <div class="small text-success mt-1"><i class="fas fa-check-double me-1"></i> 442 Terbayar Otomatis</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="small text-muted">Auto-Cut Isolir</div>
                                    <div class="fs-4 fw-bold text-danger">43 Terisolir</div>
                                    <div class="small text-muted mt-1"><i class="fas fa-sync-alt me-1"></i> Auto Restore Aktif</div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment & Notification Simulation Bar -->
                        <div class="border rounded-3 p-3 bg-white mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="small fw-bold text-dark"><i class="fab fa-whatsapp text-success me-1"></i> WhatsApp Billing Engine</span>
                                <span class="badge bg-success text-white">TERKIRIM OTOMATIS</span>
                            </div>
                            <p class="small text-muted mb-0">"Halo Bpk. Budi, tagihan internet 20 Mbps Anda Rp150.000 sudah terbayar. Router MikroTik telah diaktifkan kembali secara otomatis. Terima kasih!"</p>
                        </div>

                        <!-- Gateway Integration Badges -->
                        <div class="d-flex flex-wrap gap-2 pt-2 border-top">
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-shield-alt text-primary me-1"></i> Duitku</span>
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-bolt text-info me-1"></i> Midtrans</span>
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-credit-card text-success me-1"></i> Xendit</span>
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-qrcode text-warning me-1"></i> Tripay QRIS</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         TECH STACK LOGOS & ECOSYSTEM
    ============================================================ -->
    <section class="bg-light py-4 border-top border-bottom">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-xl-4">
                    <h5 class="mb-1 text-dark">Ekosistem Teknologi Terintegrasi</h5>
                    <p class="mb-0 small text-muted">Mendukung koneksi langsung IP Publik DDNS &amp; Auto-VPN Tunnel untuk ISP di balik CGNAT.</p>
                </div>
                <div class="col-xl-8">
                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-xl-end">
                        <div class="tech-badge-item"><i class="fas fa-network-wired text-primary"></i> MikroTik RouterOS API</div>
                        <div class="tech-badge-item"><i class="fas fa-lock text-success"></i> SSTP / WireGuard VPN</div>
                        <div class="tech-badge-item"><i class="fab fa-laravel text-danger"></i> Laravel 12 High Performance</div>
                        <div class="tech-badge-item"><i class="fab fa-whatsapp text-success"></i> Fonnte &amp; Wablas Gateway</div>
                        <div class="tech-badge-item"><i class="fas fa-qrcode text-warning"></i> Multi Payment QRIS &amp; VA</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         STATS STRIP
    ============================================================ -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-card-mc">
                        <div class="stat-num-mc">{{ number_format($stats['tenants']) }}+</div>
                        <div class="small fw-semibold text-muted">RT/RW Net &amp; ISP Terdaftar</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card-mc">
                        <div class="stat-num-mc">{{ number_format($stats['customers']) }}+</div>
                        <div class="small fw-semibold text-muted">Pelanggan Aktif Terkelola</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card-mc">
                        <div class="stat-num-mc">{{ number_format($stats['routers']) }}+</div>
                        <div class="small fw-semibold text-muted">Router MikroTik Terhubung</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card-mc">
                        <div class="stat-num-mc">{{ number_format($stats['invoices']) }}+</div>
                        <div class="small fw-semibold text-muted">Invoice Sukses Terproses</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         FEATURES (6-CARD GRID)
    ============================================================ -->
    <section class="py-lg-10 py-7 bg-light" id="features">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-xl-7">
                    <h2 class="display-6 mb-3">Fitur Lengkap untuk Pengusaha RT/RW Net &amp; ISP</h2>
                    <p class="text-muted">Semua yang Anda butuhkan untuk mengelola pelanggan, jaringan router, keuangan, dan otomasi tagihan dalam satu aplikasi.</p>
                </div>
            </div>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-lg-4 col-md-6 feature-card">
                    <div class="card bg-white border-0 h-100 p-4 shadow-sm">
                        <div class="feature-icon-box">
                            <i class="ti ti-file-invoice"></i>
                        </div>
                        <h3 class="h4 mb-2">Billing &amp; Invoice Otomatis</h3>
                        <p class="text-muted small mb-0">Sistem otomatis men-generate tagihan bulanan pada tanggal yang Anda tentukan, lengkap dengan link pembayaran instan dan rincian paket internet.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-lg-4 col-md-6 feature-card">
                    <div class="card bg-white border-0 h-100 p-4 shadow-sm">
                        <div class="feature-icon-box">
                            <i class="ti ti-router"></i>
                        </div>
                        <h3 class="h4 mb-2">Auto-Cut &amp; Auto-Restore MikroTik</h3>
                        <p class="text-muted small mb-0">Pelanggan menunggak otomatis diisolir ke profil ISOLIR pada MikroTik Anda. Begitu pelanggan membayar, koneksi internet otomatis dipulihkan detik itu juga.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-lg-4 col-md-6 feature-card">
                    <div class="card bg-white border-0 h-100 p-4 shadow-sm">
                        <div class="feature-icon-box">
                            <i class="ti ti-credit-card"></i>
                        </div>
                        <h3 class="h4 mb-2">Multi Payment Gateway Milik Anda</h3>
                        <p class="text-muted small mb-0">Uang pembayaran warga langsung masuk ke rekening Anda melalui Duitku, Midtrans, Xendit, atau Tripay, serta transfer manual bank tanpa dipotong oleh platform.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-lg-4 col-md-6 feature-card">
                    <div class="card bg-white border-0 h-100 p-4 shadow-sm">
                        <div class="feature-icon-box">
                            <i class="ti ti-brand-whatsapp"></i>
                        </div>
                        <h3 class="h4 mb-2">Notifikasi WhatsApp &amp; Email</h3>
                        <p class="text-muted small mb-0">Kirim reminder tagihan otomatis H-3, H-1, saat jatuh tempo, notifikasi pemutusan isolir, serta bukti pembayaran lunas secara otomatis ke WhatsApp pelanggan.</p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-lg-4 col-md-6 feature-card">
                    <div class="card bg-white border-0 h-100 p-4 shadow-sm">
                        <div class="feature-icon-box">
                            <i class="ti ti-shield-lock"></i>
                        </div>
                        <h3 class="h4 mb-2">Auto VPN Tunnel (Solusi CGNAT)</h3>
                        <p class="text-muted small mb-0">Router MikroTik Anda menggunakan internet rumahan tanpa IP Publik statis? Cukup paste 1 baris script SSTP/WireGuard dari MooWiFi dan router langsung online terhubung aman.</p>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="col-lg-4 col-md-6 feature-card">
                    <div class="card bg-white border-0 h-100 p-4 shadow-sm">
                        <div class="feature-icon-box">
                            <i class="ti ti-chart-bar"></i>
                        </div>
                        <h3 class="h4 mb-2">Laporan Keuangan &amp; Piutang</h3>
                        <p class="text-muted small mb-0">Pantau arus kas pendapatan, total piutang yang belum terbayar, pertumbuhan pelanggan bulanan, dan histori transaksi secara rapi dan akurat.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         HOW IT WORKS
    ============================================================ -->
    <section class="py-lg-10 py-7 bg-white" id="howItWork">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-xl-6">
                    <h2 class="display-6 mb-3">Cara Kerja MooWiFi dalam 3 Langkah</h2>
                    <p class="text-muted">Sangat mudah diintegrasikan dengan jaringan MikroTik yang sudah berjalan tanpa perlu merombak topologi.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="p-4 rounded-4 bg-light h-100 border">
                        <div class="display-5 text-primary fw-bold mb-3">01</div>
                        <h3 class="h4 mb-2">Daftar Akun &amp; Verifikasi Email</h3>
                        <p class="text-muted small mb-0">Buat akun tenant RT/RW Net baru secara gratis. Verifikasi alamat email aktif Anda untuk mengaktifkan akses penuh dasbor trial 14 hari.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-4 rounded-4 bg-light h-100 border">
                        <div class="display-5 text-primary fw-bold mb-3">02</div>
                        <h3 class="h4 mb-2">Hubungkan MikroTik &amp; Gateway</h3>
                        <p class="text-muted small mb-0">Masukkan IP Publik atau gunakan script Auto-VPN kami. Atur kredensial payment gateway dan nomor WhatsApp penagihan Anda.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-4 rounded-4 bg-light h-100 border">
                        <div class="display-5 text-primary fw-bold mb-3">03</div>
                        <h3 class="h4 mb-2">Sistem Bekerja Otomatis</h3>
                        <p class="text-muted small mb-0">Invoice terbit sendiri setiap tanggal 1, pengingat WhatsApp terkirim tepat waktu, uang masuk ke rekening, dan isolir berjalan otomatis!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PRICING SECTION (DYNAMIC FROM SAAS PLANS)
    ============================================================ -->
    <section class="py-lg-10 py-7 bg-light pricing-section overflow-hidden" id="pricing">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-xl-7">
                    <h2 class="display-6 mb-3">Pilihan Paket Langganan Platform</h2>
                    <p class="text-muted">Biaya terjangkau dengan fitur tanpa kompromi. Seluruh pendaftaran baru otomatis mendapatkan masa <strong>Uji Coba Gratis 14 Hari</strong>.</p>
                </div>
            </div>

            <div class="row justify-content-center g-4">
                @foreach($plans as $plan)
                    <div class="col-lg-4 col-md-6 pricing-card">
                        <div class="card bg-white border-0 h-100 shadow-sm p-4 d-flex flex-column {{ $plan->code === 'PRO' ? 'border border-2 border-primary position-relative' : '' }}">
                            @if($plan->code === 'PRO')
                                <div class="position-absolute top-0 start-50 translate-middle">
                                    <span class="badge text-bg-primary text-dark rounded-pill fw-semibold px-3 py-2">PALING POPULER</span>
                                </div>
                            @endif

                            <div class="text-center pt-2">
                                <span class="small fw-semibold text-secondary text-uppercase ls-md">{{ $plan->code }}</span>
                                <h3 class="h2 my-2 text-dark">{{ $plan->name }}</h3>
                                <div class="my-3">
                                    <span class="fs-1 fw-bold text-dark">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                    <span class="text-muted small">/bulan</span>
                                </div>
                                @if($plan->description)
                                    <p class="text-secondary small border-top border-bottom py-2 my-3">{{ $plan->description }}</p>
                                @endif
                            </div>

                            <ul class="list-unstyled my-4 flex-grow-1 lh-lg small">
                                @if(is_array($plan->features) && count($plan->features) > 0)
                                    @foreach($plan->features as $feat)
                                        <li class="d-flex align-items-center gap-2 mb-2">
                                            <i class="fas fa-check text-success"></i> <span>{{ $feat }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-check text-success"></i> <span>Maksimal <strong>{{ $plan->max_customers == 0 ? 'Unlimited' : $plan->max_customers }} Pelanggan</strong></span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-check text-success"></i> <span>Maksimal <strong>{{ $plan->max_routers }} Router MikroTik</strong></span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-check text-success"></i> <span>Billing &amp; Invoice Otomatis</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-check text-success"></i> <span>Payment Gateway &amp; Manual Bank</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-check text-success"></i> <span>Auto-Cut &amp; Auto-Restore MikroTik</span>
                                    </li>
                                @endif
                            </ul>

                            <div class="d-grid mt-auto">
                                <a href="{{ route('register', ['plan' => $plan->code, 'plan_id' => $plan->id]) }}" class="btn {{ $plan->code === 'PRO' ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    Pilih Paket Ini
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============================================================
         KOLOM TIKET BANTUAN & CEK STATUS TIKET (SUPPORT DESK)
    ============================================================ -->
    <section class="py-lg-10 py-7 bg-white" id="support-ticket">
        <div class="container">
            <div class="row justify-content-center text-center mb-4">
                <div class="col-xl-7">
                    <span class="badge text-bg-secondary rounded-pill py-2 px-3 mb-2">Pusat Bantuan &amp; Helpdesk</span>
                    <h2 class="display-6 mb-3">Kolom Tiket Bantuan &amp; Layanan Dukungan</h2>
                    <p class="text-muted">Memiliki kendala teknis konfigurasi MikroTik, integrasi payment gateway, atau pertanyaan langganan? Ajukan tiket bantuan atau periksa status tiket Anda di sini.</p>
                </div>
            </div>

            <!-- Alerts for Ticket Actions -->
            @if(session('ticket_created'))
                <div class="row justify-content-center mb-4">
                    <div class="col-lg-8">
                        <div class="alert alert-success shadow-sm rounded-4 p-4 border-0">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <i class="fas fa-check-circle fs-2 text-success"></i>
                                <div>
                                    <h4 class="mb-0 text-success fw-bold">Tiket Bantuan Berhasil Dibuat!</h4>
                                    <div class="small text-dark">Simpan Nomor Tiket Anda untuk melakukan pengecekan berkala.</div>
                                </div>
                            </div>
                            <div class="bg-white rounded-3 p-3 mt-3 border">
                                <div class="row g-2">
                                    <div class="col-sm-6">
                                        <span class="text-muted small">Nomor Tiket:</span>
                                        <div class="fs-4 fw-bold font-monospace text-primary">{{ session('ticket_created')['number'] }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="text-muted small">Email Pelapor:</span>
                                        <div class="fw-semibold text-dark">{{ session('ticket_created')['email'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('ticket_error'))
                <div class="row justify-content-center mb-4">
                    <div class="col-lg-8">
                        <div class="alert alert-danger shadow-sm rounded-4 p-3 border-0 d-flex align-items-center gap-2">
                            <i class="fas fa-exclamation-circle fs-3"></i>
                            <div>{{ session('ticket_error') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('ticket_found'))
                @php $found = session('ticket_found'); @endphp
                <div class="row justify-content-center mb-4">
                    <div class="col-lg-8">
                        <div class="card border border-2 border-primary rounded-4 shadow-sm overflow-hidden">
                            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <span class="badge bg-warning text-dark font-monospace me-2">{{ $found->ticket_number }}</span>
                                    <span class="fw-semibold">{{ $found->subject }}</span>
                                </div>
                                <div>
                                    @if($found->status === 'OPEN')
                                        <span class="badge bg-danger">MENUNGGU TANGGAPAN</span>
                                    @elseif($found->status === 'IN_PROGRESS')
                                        <span class="badge bg-warning text-dark">SEDANG DIPROSES</span>
                                    @elseif($found->status === 'RESOLVED')
                                        <span class="badge bg-success">TERJAWAB</span>
                                    @else
                                        <span class="badge bg-secondary">DITUTUP</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <span class="text-muted small">Pesan Pelapor ({{ $found->created_at->format('d M Y H:i') }}):</span>
                                    <div class="bg-light p-3 rounded-3 mt-1 text-dark" style="white-space: pre-wrap;">{{ $found->message }}</div>
                                </div>

                                @if($found->admin_reply)
                                    <div class="border-top pt-3">
                                        <div class="d-flex align-items-center gap-2 mb-2 text-success fw-bold">
                                            <i class="fas fa-user-shield"></i> <span>Tanggapan Resmi Tim Dukungan Super Admin:</span>
                                        </div>
                                        <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 text-dark" style="white-space: pre-wrap;">{{ $found->admin_reply }}</div>
                                        <small class="text-muted d-block mt-1">Dibalas pada: {{ $found->replied_at?->format('d M Y H:i') }}</small>
                                    </div>
                                @else
                                    <div class="alert alert-info py-2 small mb-0">
                                        <i class="fas fa-info-circle me-1"></i> Tiket Anda sedang dalam antrean penanganan oleh tim technical support kami. Mohon cek kembali secara berkala.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <ul class="nav nav-pills nav-pills-mc justify-content-center mb-4" id="ticketTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="create-tab" data-bs-toggle="pill" data-bs-target="#tab-create" type="button" role="tab">
                                <i class="ti ti-plus me-1"></i> Kirim Tiket Bantuan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="check-tab" data-bs-toggle="pill" data-bs-target="#tab-check" type="button" role="tab">
                                <i class="ti ti-search me-1"></i> Cek Status Tiket
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="ticketTabContent">
                        <!-- Tab 1: Create Ticket -->
                        <div class="tab-pane fade show active" id="tab-create" role="tabpanel">
                            <div class="card border rounded-4 p-4 shadow-sm">
                                <h3 class="h4 mb-3 text-dark">Formulir Tiket Dukungan Teknis</h3>
                                <form action="{{ route('landing.ticket.store') }}" method="POST">
                                    @csrf
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label required small fw-bold">Nama Lengkap</label>
                                            <input type="text" name="name" class="form-control" placeholder="Nama Anda atau Brand RT/RW Net" value="{{ old('name') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required small fw-bold">Alamat Email</label>
                                            <input type="email" name="email" class="form-control" placeholder="alamat@email.com" value="{{ old('email') }}" required>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Nomor WhatsApp (Opsional)</label>
                                            <input type="text" name="phone" class="form-control" placeholder="08123456789" value="{{ old('phone') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required small fw-bold">Kategori Kendala</label>
                                            <select name="category" class="form-select" required>
                                                <option value="GENERAL">Pertanyaan Umum / Info Produk</option>
                                                <option value="MIKROTIK">Koneksi MikroTik &amp; VPN Tunneling</option>
                                                <option value="BILLING">Billing &amp; Payment Gateway</option>
                                                <option value="ACCOUNT">Pendaftaran Akun &amp; Langganan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required small fw-bold">Subjek Masalah</label>
                                        <input type="text" name="subject" class="form-control" placeholder="Contoh: Bantuan setting VPN MikroTik tanpa IP Publik" value="{{ old('subject') }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label required small fw-bold">Rincian Kendala / Pertanyaan</label>
                                        <textarea name="message" rows="4" class="form-control" placeholder="Jelaskan detail kendala, tipe router MikroTik, atau pertanyaan Anda..." required>{{ old('message') }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-2">
                                        <i class="ti ti-send me-1"></i> Kirimkan Tiket Bantuan
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Tab 2: Check Ticket -->
                        <div class="tab-pane fade" id="tab-check" role="tabpanel">
                            <div class="card border rounded-4 p-4 shadow-sm">
                                <h3 class="h4 mb-2 text-dark">Pengecekan Status Tiket</h3>
                                <p class="text-muted small mb-4">Masukkan Nomor Tiket yang Anda peroleh saat pengajuan tiket beserta alamat email terdaftar.</p>

                                <form action="{{ route('landing.ticket.check') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label required small fw-bold">Nomor Tiket</label>
                                        <input type="text" name="ticket_number" class="form-control font-monospace text-uppercase" placeholder="Contoh: TKT-2610-ABC12" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label required small fw-bold">Alamat Email Pelapor</label>
                                        <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                                    </div>
                                    <button type="submit" class="btn btn-outline-secondary w-100 py-2">
                                        <i class="ti ti-search me-1"></i> Lacak Status Tiket Saya
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         FAQ SECTION
    ============================================================ -->
    <section class="py-lg-10 py-7 bg-light" id="faq">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-xl-6">
                    <h2 class="display-6 mb-3">Pertanyaan yang Sering Diajukan</h2>
                    <p class="text-muted">Informasi teknis dan praktis seputar implementasi MooWiFi di jaringan RT/RW Net Anda.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="accordion" id="accordionMooWiFi">
                        <!-- FAQ 1 -->
                        <div class="border mb-3 rounded-4 p-4 bg-white shadow-sm">
                            <h3 class="h5 mb-0">
                                <a href="#" class="text-reset d-flex justify-content-between align-items-center text-decoration-none" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    <span>Apakah bisa dipakai jika router MikroTik tidak punya IP Publik Statis?</span>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </a>
                            </h3>
                            <div id="faq1" class="collapse show" data-bs-parent="#accordionMooWiFi">
                                <div class="mt-3 text-muted small pt-2 border-top">
                                    Bisa 100%! MooWiFi menyediakan fitur <strong>Auto VPN Tunneling (SSTP &amp; WireGuard)</strong>. Anda cukup meng-copy 1 baris script yang di-generate dari dasbor MooWiFi ke Terminal Winbox MikroTik Anda, dan router langsung terhubung ke sistem tanpa perlu sewa IP publik.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="border mb-3 rounded-4 p-4 bg-white shadow-sm">
                            <h3 class="h5 mb-0">
                                <a href="#" class="text-reset d-flex justify-content-between align-items-center text-decoration-none collapsed" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    <span>Kemana uang pembayaran tagihan internet pelanggan masuk?</span>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </a>
                            </h3>
                            <div id="faq2" class="collapse" data-bs-parent="#accordionMooWiFi">
                                <div class="mt-3 text-muted small pt-2 border-top">
                                    Uang pembayaran tagihan <strong>100% langsung masuk ke rekening atau akun merchant payment gateway milik Anda sendiri</strong> (Duitku, Midtrans, Xendit, Tripay, atau rekening bank manual). MooWiFi tidak menampung uang pembayaran warga.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="border mb-3 rounded-4 p-4 bg-white shadow-sm">
                            <h3 class="h5 mb-0">
                                <a href="#" class="text-reset d-flex justify-content-between align-items-center text-decoration-none collapsed" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    <span>Apakah pendaftaran membutuhkan verifikasi email?</span>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </a>
                            </h3>
                            <div id="faq3" class="collapse" data-bs-parent="#accordionMooWiFi">
                                <div class="mt-3 text-muted small pt-2 border-top">
                                    Ya. Demi keamanan akun dan mencegah penyalahgunaan platform, setiap pendaftar baru wajib memverifikasi kepemilikan email melalui link verifikasi resmi yang dikirimkan oleh server platform kami.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="border mb-3 rounded-4 p-4 bg-white shadow-sm">
                            <h3 class="h5 mb-0">
                                <a href="#" class="text-reset d-flex justify-content-between align-items-center text-decoration-none collapsed" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    <span>Bagaimana cara kerja Auto-Cut dan Auto-Restore MikroTik?</span>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </a>
                            </h3>
                            <div id="faq4" class="collapse" data-bs-parent="#accordionMooWiFi">
                                <div class="mt-3 text-muted small pt-2 border-top">
                                    Jika pelanggan melewati batas jatuh tempo dan masa tenggang (grace period), sistem scheduler otomatis mengubah profil PPP Secret / Simple Queue pelanggan menjadi profil isolir (kecepatan dibatasi atau dialihkan ke halaman peringatan isolir). Begitu tagihan dibayar lunas, webhook gateway memicu sistem untuk langsung me-restore profil normal secara realtime.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         FOOTER
    ============================================================ -->
    <footer class="bg-secondary pt-lg-10 pt-7 text-white-50">
        <div class="container">
            <div class="row g-5">
                <!-- Col 1: Brand -->
                <div class="col-lg-4 col-12">
                    <a class="d-inline-flex gap-2 align-items-center text-decoration-none" href="#">
                        <img src="{{ asset('logo.png') }}" alt="MooWiFi Logo" class="img-fluid" style="height: 34px; border-radius: 6px;">
                        <span style="font-weight: 700; font-size: 1.35rem; color: #ffffff; letter-spacing: -0.02em;">MOO<span style="color: #FF8D6D;">WIFI</span></span>
                    </a>
                    <p class="mt-3 text-white-50 small">
                        Solusi perangkat lunak SaaS terintegrasi untuk automasi penagihan invoice, integrasi MikroTik RouterOS, multi payment gateway, dan manajemen operasional bisnis RT/RW Net di Indonesia.
                    </p>
                </div>

                <!-- Col 2: Fitur Utama -->
                <div class="col-lg-3 col-6">
                    <h4 class="text-white h5 mb-3">Fitur Utama</h4>
                    <ul class="list-unstyled lh-lg small">
                        <li><a href="#features" class="text-reset text-decoration-none">Billing Otomatis Bulanan</a></li>
                        <li><a href="#features" class="text-reset text-decoration-none">Auto-Cut &amp; Auto-Restore MikroTik</a></li>
                        <li><a href="#features" class="text-reset text-decoration-none">Auto-VPN Tunneling (CGNAT)</a></li>
                        <li><a href="#features" class="text-reset text-decoration-none">Gateway Duitku, Xendit, Midtrans</a></li>
                        <li><a href="#features" class="text-reset text-decoration-none">Notifikasi Tagihan WhatsApp</a></li>
                    </ul>
                </div>

                <!-- Col 3: Navigasi Cepat -->
                <div class="col-lg-2 col-6">
                    <h4 class="text-white h5 mb-3">Navigasi</h4>
                    <ul class="list-unstyled lh-lg small">
                        <li><a href="#features" class="text-reset text-decoration-none">Fitur</a></li>
                        <li><a href="#pricing" class="text-reset text-decoration-none">Paket Harga</a></li>
                        <li><a href="#support-ticket" class="text-reset text-decoration-none">Tiket Bantuan</a></li>
                        <li><a href="#faq" class="text-reset text-decoration-none">FAQ</a></li>
                        <li><a href="{{ route('register') }}" class="text-reset text-decoration-none">Daftar Akun Baru</a></li>
                    </ul>
                </div>

                <!-- Col 4: Layanan Kontak -->
                <div class="col-lg-3 col-12">
                    <h4 class="text-white h5 mb-3">Layanan Dukungan</h4>
                    <ul class="list-unstyled lh-lg small">
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="ti ti-ticket text-primary"></i> <a href="#support-ticket" class="text-reset text-decoration-none">Helpdesk &amp; Tiket Bantuan</a>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="ti ti-brand-whatsapp text-primary"></i> <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="text-reset text-decoration-none">Hotline WhatsApp</a>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="ti ti-mail text-primary"></i> <a href="mailto:support@moowifi.id" class="text-reset text-decoration-none">support@moowifi.id</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row border-top py-4 mt-5 g-md-0 gy-2 small border-opacity-10 border-white text-white-50">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} MooWiFi Platform. Hak Cipta Dilindungi.</p>
                </div>
                <div class="col-md-6 d-flex flex-column flex-md-row justify-content-md-end gap-3 align-items-md-center">
                    <div><span>System Status:</span> <span class="text-white fw-semibold">100% Operational</span></div>
                    <div><a href="{{ route('login') }}" class="text-white text-decoration-none"><i class="fas fa-lock me-1"></i> Portal Masuk Dasbor</a></div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
