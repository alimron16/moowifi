<!DOCTYPE html>
<html lang="id" style="scroll-behavior: smooth;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title>Tentang Kami - MooWiFi | Solusi SaaS Billing &amp; Manajemen Jaringan RT/RW Net</title>
    <meta name="title" content="Tentang Kami - MooWiFi | Solusi SaaS Billing &amp; Manajemen Jaringan RT/RW Net">
    <meta name="description" content="Mengenal MooWiFi: Platform SaaS teknologi otomasi billing, integrasi router MikroTik, dan payment gateway yang dirancang khusus untuk memajukan bisnis RT/RW Net dan ISP lokal di seluruh Indonesia.">
    <meta name="keywords" content="tentang moowifi, profil moowifi, billing rtrw net, software rtrw net, visi misi moowifi, alamat moowifi tangerang selatan">
    <meta name="author" content="MooWiFi Platform">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/tentang-kami') }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:url" content="{{ url('/tentang-kami') }}">
    <meta property="og:site_name" content="MooWiFi">
    <meta property="og:title" content="Tentang Kami - MooWiFi | Solusi SaaS Billing &amp; Manajemen RT/RW Net">
    <meta property="og:description" content="Platform teknologi cloud untuk digitalisasi manajemen pelanggan, router MikroTik, dan penagihan internet RT/RW Net di Indonesia.">
    <meta property="og:image" content="{{ asset('logo.png') }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.36.0/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --mc-orange: #FF8D6D;
            --mc-dark-green: #003A43;
            --mc-forest: #006E2F;
            --mc-teal: #00B8DB;
            --mc-amber: #F0B100;
            --mc-gray-100: #F6F8F9;
            --mc-gray-200: #E5E9EB;
            --mc-gray-300: #D0D7DA;
            --mc-gray-600: #627175;
            --mc-gray-700: #4B585C;
            --mc-gray-800: #293437;
            --mc-gray-900: #161B1D;
        }

        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--mc-gray-800);
            background-color: #ffffff;
            line-height: 1.65;
        }

        .navbar-main {
            background-color: #ffffff;
            border-bottom: 1px solid var(--mc-gray-200);
            padding-top: 14px;
            padding-bottom: 14px;
            position: sticky;
            top: 0;
            z-index: 1040;
            box-shadow: 0 2px 10px rgba(0, 58, 67, 0.04);
        }

        .btn-primary {
            background-color: var(--mc-orange);
            border-color: var(--mc-orange);
            color: var(--mc-gray-900);
            font-weight: 600;
            border-radius: 9999px;
            padding: 9px 24px;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #ff7650;
            border-color: #ff7650;
            color: var(--mc-gray-900);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 141, 109, 0.35);
        }

        .btn-outline-secondary {
            color: var(--mc-dark-green);
            border-color: var(--mc-dark-green);
            background: transparent;
            font-weight: 600;
            border-radius: 9999px;
            padding: 9px 22px;
            transition: all 0.2s ease;
        }

        .btn-outline-secondary:hover {
            background-color: rgba(0, 58, 67, 0.08);
            color: var(--mc-dark-green);
            border-color: var(--mc-dark-green);
            transform: translateY(-2px);
        }

        .bg-secondary {
            background-color: var(--mc-dark-green) !important;
        }

        .page-header-box {
            background: linear-gradient(135deg, rgba(0, 58, 67, 0.04) 0%, rgba(255, 141, 109, 0.08) 100%);
            border-bottom: 1px solid var(--mc-gray-200);
            padding: 70px 0 60px;
        }

        .about-card {
            background: #ffffff;
            border: 1px solid var(--mc-gray-200);
            border-radius: 20px;
            padding: 32px 28px;
            box-shadow: 0 4px 20px rgba(0, 58, 67, 0.04);
            height: 100%;
            transition: all 0.25s ease;
        }

        .about-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 32px rgba(0, 58, 67, 0.08);
            border-color: var(--mc-orange);
        }

        .icon-bubble {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: rgba(0, 58, 67, 0.08);
            color: var(--mc-dark-green);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 20px;
        }

        .stat-badge {
            background-color: #ffffff;
            border: 1px solid var(--mc-gray-200);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
        }

        .stat-badge .num {
            font-size: 2.3rem;
            font-weight: 700;
            color: var(--mc-dark-green);
            line-height: 1.1;
        }

        .footer-section {
            padding-top: 70px;
            padding-bottom: 30px;
        }

        .hover-white:hover {
            color: #ffffff !important;
        }
    </style>
</head>
<body>

    <!-- ============================================================
         NAVBAR
    ============================================================ -->
    <nav class="navbar navbar-expand-lg navbar-main">
        <div class="container">
            <a class="navbar-brand d-inline-flex gap-2 align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('logo.png') }}" alt="MooWiFi" style="height: 36px; border-radius: 6px;">
                <span style="font-weight: 700; font-size: 1.35rem; color: #003A43; letter-spacing: -0.02em;">MOO<span style="color: #FF8D6D;">WIFI</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="{{ url('/') }}#features" class="nav-link fw-semibold text-dark px-3">Fitur</a></li>
                    <li class="nav-item"><a href="{{ url('/') }}#howItWork" class="nav-link fw-semibold text-dark px-3">Cara Kerja</a></li>
                    <li class="nav-item"><a href="{{ url('/') }}#pricing" class="nav-link fw-semibold text-dark px-3">Paket Harga</a></li>
                    <li class="nav-item"><a href="{{ route('landing.about') }}" class="nav-link fw-bold text-primary px-3">Tentang Kami</a></li>
                    <li class="nav-item"><a href="{{ url('/') }}#support-ticket" class="nav-link fw-semibold text-dark px-3">Tiket Bantuan</a></li>
                    <li class="nav-item"><a href="{{ url('/') }}#faq" class="nav-link fw-semibold text-dark px-3">FAQ</a></li>
                </ul>
                <div class="d-flex gap-2 align-items-center">
                    @auth
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ route('super-admin.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-login me-1"></i> Login
                            </a>
                        @else
                            <a href="{{ route('tenant.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-dashboard me-1"></i> Dasbor
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-login me-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <span>Coba Gratis 30 Hari</span> <i class="ti ti-arrow-right fs-6"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- ============================================================
         PAGE HEADER
    ============================================================ -->
    <header class="page-header-box">
        <div class="container text-center">
            <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill fw-semibold mb-3 shadow-sm">
                <i class="ti ti-sparkles text-primary me-1"></i> Platform SaaS RT/RW Net &amp; ISP Lokal No. 1
            </span>
            <h1 class="display-4 fw-bold text-dark mb-3">Tentang MooWiFi</h1>
            <p class="lead text-muted mx-auto" style="max-width: 760px;">
                Kami berdedikasi membangun perangkat lunak modern untuk mengotomatisasi operasional, tagihan bulanan, dan integrasi router bagi ribuan wirausahawan internet komunitas di Indonesia.
            </p>
        </div>
    </header>

    <!-- ============================================================
         CERITA & MISI
    ============================================================ -->
    <section class="py-5">
        <div class="container py-lg-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="text-primary fw-bold text-uppercase small tracking-wide">Cerita &amp; Latar Belakang</span>
                    <h2 class="display-6 fw-bold text-dark mt-2 mb-4">
                        Mengubah Penagihan Manual Menjadi Ekosistem Otomatis 24 Jam Nonstop
                    </h2>
                    <p class="text-secondary lh-lg mb-3">
                        Bisnis RT/RW Net dan ISP lokal memegang peranan krusial dalam memeratakan akses internet pita lebar (broadband) ke gang-gang, perumahan, hingga pelosok pedesaan di seluruh nusantara. Namun, sebagian besar pemilik jaringan masih terjebak dalam rutinitas operasional yang menguras tenaga:
                    </p>
                    <ul class="text-secondary lh-lg mb-4 ps-3">
                        <li>Mencatat pembayaran pelanggan secara manual di buku atau spreadsheet yang rentan selisih.</li>
                        <li>Menagih dari pintu ke pintu atau mengirim pesan WhatsApp satu per satu setiap awal bulan.</li>
                        <li>Harus membuka Winbox secara manual tengah malam hanya untuk mengisolir atau membuka blokir pelanggan.</li>
                        <li>Kesulitan mengintegrasikan payment gateway perbankan karena keterbatasan teknis dan biaya server.</li>
                    </ul>
                    <p class="text-secondary lh-lg">
                        <strong>MooWiFi lahir untuk menyelesaikan masalah tersebut.</strong> Kami menyediakan platform cloud yang menghubungkan perangkat lunak penagihan pintar dengan router MikroTik RouterOS Anda secara aman, instan, dan handal.
                    </p>
                </div>

                <div class="col-lg-6">
                    <div class="p-4 p-lg-5 rounded-4" style="background: linear-gradient(145deg, #003A43 0%, #002227 100%); color: #ffffff;">
                        <h3 class="h3 fw-bold text-white mb-4">
                            Visi &amp; Komitmen Kami
                        </h3>
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="icon-bubble bg-white bg-opacity-10 text-white flex-shrink-0">
                                <i class="ti ti-target"></i>
                            </div>
                            <div>
                                <h4 class="h5 fw-bold text-white mb-1">Visi Kami</h4>
                                <p class="text-white-50 small mb-0 lh-lg">
                                    Menjadi ekosistem teknologi perangkat lunak manajemen billing &amp; router No. 1 di Asia Tenggara yang mendemokratisasi akses otomasi digital bagi setiap penyedia internet komunitas.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3">
                            <div class="icon-bubble bg-white bg-opacity-10 text-white flex-shrink-0">
                                <i class="ti ti-rocket"></i>
                            </div>
                            <div>
                                <h4 class="h5 fw-bold text-white mb-1">Misi Kami</h4>
                                <p class="text-white-50 small mb-0 lh-lg">
                                    Memberikan alat otomasi yang mudah digunakan, bebas kendala teknis server, mendukung router di balik CGNAT (tanpa IP publik), dan memastikan arus kas pengusaha jaringan tumbuh sehat dan transparan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         4 PILAR NILAI MOOWIFI
    ============================================================ -->
    <section class="py-5 bg-light">
        <div class="container py-lg-4">
            <div class="text-center mb-5">
                <span class="text-primary fw-bold text-uppercase small">Keunggulan Fondasi</span>
                <h2 class="display-6 fw-bold text-dark mt-2">Prinsip &amp; Pilar Teknologi MooWiFi</h2>
                <p class="text-muted mx-auto" style="max-width: 650px;">
                    Setiap fitur di MooWiFi dirancang dengan mempertimbangkan efisiensi kerja, perlindungan data, dan kedaulatan finansial Anda.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="about-card">
                        <div class="icon-bubble">
                            <i class="ti ti-wallet"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">100% Milik Anda</h3>
                        <p class="text-muted small mb-0 lh-lg">
                            MooWiFi <strong>bukan</strong> penampung dana. Seluruh pembayaran tagihan pelanggan masuk 100% langsung ke rekening bank atau merchant payment gateway Anda sendiri.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="about-card">
                        <div class="icon-bubble">
                            <i class="ti ti-router"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Solusi Router CGNAT</h3>
                        <p class="text-muted small mb-0 lh-lg">
                            Tidak memiliki IP Publik Statis? Teknologi Auto VPN Tunneling (WireGuard &amp; SSTP) kami memungkinkan router rumahan terhubung ke sistem hanya dengan 1 baris script.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="about-card">
                        <div class="icon-bubble">
                            <i class="ti ti-shield-check"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Multi-Tenancy Aman</h3>
                        <p class="text-muted small mb-0 lh-lg">
                            Setiap tenant terisolasi ketat di level backend. Data pelanggan, router, dan laporan keuangan Anda terlindungi penuh dan tidak pernah bercampur dengan tenant lain.
                        </p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="about-card">
                        <div class="icon-bubble">
                            <i class="ti ti-bolt"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-2">Otomasi Realtime</h3>
                        <p class="text-muted small mb-0 lh-lg">
                            Auto Cut saat jatuh tempo dan Auto Restore dalam hitungan detik setelah pelanggan membayar, tanpa perlu campur tangan manual admin router.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         ALAMAT USAHA & IDENTITAS RESMI
    ============================================================ -->
    <section class="py-5">
        <div class="container py-lg-4">
            <div class="card border rounded-4 shadow-sm overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-6 p-4 p-lg-5 bg-white">
                        <span class="badge bg-primary-lt text-dark fw-bold mb-3 px-3 py-2 rounded-pill">
                            <i class="ti ti-building me-1 text-primary"></i> Identitas &amp; Alamat Resmi
                        </span>
                        <h3 class="h2 fw-bold text-dark mb-3">Kantor Operasional Platform</h3>
                        <p class="text-secondary lh-lg mb-4">
                            MooWiFi dikelola secara profesional dan beroperasi di bawah payung manajemen resmi di Kota Tangerang Selatan, Banten.
                        </p>

                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="icon-bubble mb-0 flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.3rem;">
                                <i class="ti ti-map-pin"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block">Alamat Usaha:</span>
                                <div class="text-secondary">
                                    Gang Mahi Salam, RT 001/021, No. 25,<br>
                                    Kelurahan Parigi, Kecamatan Pondok Aren,<br>
                                    Kota Tangerang Selatan, Banten 15228, Indonesia
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-bubble mb-0 flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.3rem;">
                                <i class="ti ti-mail"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block">Email Dukungan &amp; Kemitraan:</span>
                                <a href="mailto:support@moowifi.id" class="text-decoration-none text-primary fw-medium">support@moowifi.id</a>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-bubble mb-0 flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.3rem;">
                                <i class="ti ti-brand-whatsapp"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block">Hotline WhatsApp:</span>
                                <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="text-decoration-none text-success fw-medium">+62 812-3456-7890</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 p-4 p-lg-5 text-white" style="background-color: var(--mc-dark-green);">
                        <h3 class="h3 fw-bold text-white mb-3">Pernyataan Hukum &amp; Batasan Layanan</h3>
                        <p class="text-white-50 lh-lg mb-4 small">
                            Sebagai penyedia platform perangkat lunak (Software as a Service - SaaS), kami menjunjung tinggi kepatuhan regulasi telekomunikasi dan hukum di Indonesia:
                        </p>
                        <div class="border-start border-2 border-primary ps-3 mb-3">
                            <strong class="text-white d-block small">Penyedia Perangkat Lunak, Bukan ISP</strong>
                            <p class="text-white-50 small mb-0">
                                MooWiFi semata-mata merupakan penyedia software billing, manajemen pelanggan, dan otomasi router. MooWiFi <strong>bukan Penyelenggara Jasa Internet (ISP)</strong> dan tidak menjual bandwidth internet.
                            </p>
                        </div>
                        <div class="border-start border-2 border-primary ps-3 mb-4">
                            <strong class="text-white d-block small">Kepatuhan Hukum Pengguna (Tenant)</strong>
                            <p class="text-white-50 small mb-0">
                                Setiap pengusaha RT/RW Net atau Tenant bertanggung jawab penuh atas perizinan operasional, izin frekuensi, serta kemitraan resmi dengan ISP berizin Kominfo di wilayah operasional masing-masing.
                            </p>
                        </div>
                        <a href="{{ route('landing.terms') }}" class="btn btn-outline-light rounded-pill px-4 btn-sm">
                            <i class="ti ti-file-text me-1"></i> Baca Syarat &amp; Ketentuan Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         CALL TO ACTION
    ============================================================ -->
    <section class="py-5" style="background: linear-gradient(135deg, rgba(255, 141, 109, 0.1) 0%, rgba(0, 58, 67, 0.05) 100%);">
        <div class="container text-center py-4">
            <h2 class="display-6 fw-bold text-dark mb-3">Siap Mengotomatisasi Bisnis RT/RW Net Anda?</h2>
            <p class="lead text-muted mx-auto mb-4" style="max-width: 600px;">
                Coba MooWiFi gratis selama 30 hari penuh. Tanpa perlu kartu kredit, tanpa kontrak mengikat, dan dapat dibatalkan kapan saja.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                    <span>Mulai Coba Gratis Sekarang</span> <i class="ti ti-arrow-right ms-1"></i>
                </a>
                <a href="{{ url('/') }}#features" class="btn btn-outline-secondary btn-lg">
                    Lihat Semua Fitur
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================
         FOOTER
    ============================================================ -->
    <footer class="bg-secondary footer-section text-white-50">
        <div class="container">
            <div class="row g-5">
                <!-- Col 1: Brand -->
                <div class="col-lg-4 col-12">
                    <a class="d-inline-flex gap-2 align-items-center text-decoration-none" href="{{ url('/') }}">
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
                        <li><a href="{{ url('/') }}#features" class="text-reset text-decoration-none">Billing Otomatis Bulanan</a></li>
                        <li><a href="{{ url('/') }}#features" class="text-reset text-decoration-none">Auto-Cut &amp; Auto-Restore MikroTik</a></li>
                        <li><a href="{{ url('/') }}#features" class="text-reset text-decoration-none">Auto-VPN Tunneling (CGNAT)</a></li>
                        <li><a href="{{ url('/') }}#features" class="text-reset text-decoration-none">Gateway Duitku, Xendit, Midtrans</a></li>
                        <li><a href="{{ url('/') }}#features" class="text-reset text-decoration-none">Notifikasi Tagihan WhatsApp</a></li>
                    </ul>
                </div>

                <!-- Col 3: Navigasi Cepat -->
                <div class="col-lg-2 col-6">
                    <h4 class="text-white h5 mb-3">Navigasi</h4>
                    <ul class="list-unstyled lh-lg small">
                        <li><a href="{{ url('/') }}#features" class="text-reset text-decoration-none">Fitur</a></li>
                        <li><a href="{{ url('/') }}#pricing" class="text-reset text-decoration-none">Paket Harga</a></li>
                        <li><a href="{{ route('landing.about') }}" class="text-white fw-bold text-decoration-none">Tentang Kami</a></li>
                        <li><a href="{{ route('landing.terms') }}" class="text-reset text-decoration-none">Syarat &amp; Ketentuan</a></li>
                        <li><a href="{{ url('/') }}#support-ticket" class="text-reset text-decoration-none">Tiket Bantuan</a></li>
                        <li><a href="{{ url('/') }}#faq" class="text-reset text-decoration-none">FAQ</a></li>
                        <li><a href="{{ route('register') }}" class="text-reset text-decoration-none">Daftar Akun Baru</a></li>
                    </ul>
                </div>

                <!-- Col 4: Layanan Kontak & Alamat Usaha -->
                <div class="col-lg-3 col-12">
                    <h4 class="text-white h5 mb-3">Kantor &amp; Kontak</h4>
                    <ul class="list-unstyled lh-lg small">
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="ti ti-map-pin text-primary mt-1 fs-5"></i>
                            <div>
                                <span class="text-white fw-bold d-block">Alamat Usaha:</span>
                                <span class="text-white-50">
                                    Gang Mahi Salam, RT 001/021, No. 25,<br>
                                    Parigi, Pondok Aren,<br>
                                    Kota Tangerang Selatan
                                </span>
                            </div>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="ti ti-brand-whatsapp text-primary"></i> <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="text-reset text-decoration-none">Hotline WhatsApp</a>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="ti ti-mail text-primary"></i> <a href="mailto:support@moowifi.id" class="text-reset text-decoration-none">support@moowifi.id</a>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="ti ti-ticket text-primary"></i> <a href="{{ url('/') }}#support-ticket" class="text-reset text-decoration-none">Helpdesk &amp; Tiket Bantuan</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-top py-4 mt-5 small border-opacity-10 border-white text-white-50 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="mb-0">&copy; {{ date('Y') }} MooWiFi Platform. Hak Cipta Dilindungi.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('landing.about') }}" class="text-white-50 text-decoration-none hover-white">Tentang Kami</a>
                    <span>&bull;</span>
                    <a href="{{ route('landing.terms') }}" class="text-white-50 text-decoration-none hover-white">Syarat dan Ketentuan Pengguna</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
