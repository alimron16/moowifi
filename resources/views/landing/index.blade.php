<!DOCTYPE html>
<html lang="id" style="scroll-behavior: smooth; scroll-padding-top: 80px;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Primary SEO Meta Tags -->
    <title>MooWiFi - Aplikasi SaaS Billing &amp; Manajemen RT/RW Net | Otomasi MikroTik &amp; Payment Gateway</title>
    <meta name="title" content="MooWiFi - Aplikasi SaaS Billing &amp; Manajemen RT/RW Net | Otomasi MikroTik &amp; Payment Gateway">
    <meta name="description" content="MooWiFi adalah aplikasi SaaS Billing &amp; Manajemen RT/RW Net / ISP modern No. 1 di Indonesia. Dilengkapi otomasi MikroTik Auto-Cut &amp; Auto-Restore, invoice WhatsApp otomatis, dan integrasi multi payment gateway (Duitku, Midtrans, Xendit, Tripay, QRIS, Virtual Account). Coba gratis 14 hari!">
    <meta name="keywords" content="billing rtrw net, aplikasi rtrw net, software rtrw net, billing isp, billing mikrotik, auto cut mikrotik, auto restore mikrotik, payment gateway rtrw net, invoice whatsapp otomatis, aplikasi kasir wifi rtrw net, vpn cgnat mikrotik, duitku rtrw net, tripay rtrw net, midtrans rtrw net, xendit rtrw net, manajemen hotspot pppoe, moowifi">
    <meta name="author" content="MooWiFi Platform">
    <meta name="language" content="Indonesian">
    <meta name="geo.region" content="ID">
    <meta name="geo.placename" content="Indonesia">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Search Engine Crawlers & Indexing Directives -->
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    <!-- Open Graph / Facebook / WhatsApp / Telegram Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:site_name" content="MooWiFi">
    <meta property="og:title" content="MooWiFi - Aplikasi SaaS Billing &amp; Manajemen RT/RW Net Terintegrasi MikroTik">
    <meta property="og:description" content="Kelola ratusan pelanggan internet RT/RW Net &amp; ISP tanpa ribet. Auto Cut &amp; Restore MikroTik 24 jam nonstop, invoice WhatsApp otomatis, dan terima pembayaran QRIS/VA langsung ke rekening Anda.">
    <meta property="og:image" content="{{ asset('logo.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="MooWiFi Platform Billing RT/RW Net &amp; Otomasi MikroTik">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="MooWiFi - Aplikasi SaaS Billing &amp; Manajemen RT/RW Net Terintegrasi MikroTik">
    <meta name="twitter:description" content="Software billing dan manajemen RT/RW Net modern di Indonesia. Otomasi MikroTik, Invoice WhatsApp, dan Multi Payment Gateway.">
    <meta name="twitter:image" content="{{ asset('logo.png') }}">

    <!-- Favicon & Touch Icons -->
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">

    <!-- Google Fonts: IBM Plex Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons Webfont & Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.36.0/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Structured Data: JSON-LD Schema.org for Google Search Rich Snippets & Sitelinks -->
    @php
        $schemaOrgData = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'SoftwareApplication',
                    '@id' => url('/') . '#software',
                    'name' => 'MooWiFi',
                    'alternateName' => 'MooWiFi Billing RT/RW Net',
                    'applicationCategory' => 'BusinessApplication',
                    'operatingSystem' => 'All, Cloud Web-Based',
                    'url' => url('/'),
                    'description' => 'Platform SaaS Billing & Manajemen RT/RW Net / ISP modern di Indonesia dengan otomasi MikroTik Auto-Cut & Auto-Restore, WhatsApp billing notification, dan multi payment gateway.',
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => '100000',
                        'priceCurrency' => 'IDR',
                        'availability' => 'https://schema.org/InStock',
                        'priceValidUntil' => date('Y-12-31'),
                    ],
                    'featureList' => [
                        'Billing & Invoice Otomatis Bulanan',
                        'Auto-Cut & Auto-Restore MikroTik RouterOS',
                        'Auto-VPN Tunneling Solusi CGNAT / IndiHome',
                        'Notifikasi Pengingat Tagihan WhatsApp Otomatis',
                        'Multi Payment Gateway Duitku, Midtrans, Xendit, Tripay, QRIS & VA',
                        'Laporan Arus Kas Pendapatan & Piutang Realtime',
                        'Ticketing Helpdesk Bantuan Teknis',
                    ],
                ],
                [
                    '@type' => 'Organization',
                    '@id' => url('/') . '#organization',
                    'name' => 'MooWiFi',
                    'url' => url('/'),
                    'logo' => asset('logo.png'),
                    'sameAs' => [
                        'https://wa.me/6281234567890',
                    ],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'telephone' => '+62-812-3456-7890',
                        'contactType' => 'customer service',
                        'areaServed' => 'ID',
                        'availableLanguage' => ['Indonesian', 'English'],
                    ],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => url('/') . '#website',
                    'url' => url('/'),
                    'name' => 'MooWiFi Platform',
                    'publisher' => [
                        '@id' => url('/') . '#organization',
                    ],
                    'inLanguage' => 'id',
                ],
                [
                    '@type' => 'FAQPage',
                    '@id' => url('/') . '#faq',
                    'mainEntity' => [
                        [
                            '@type' => 'Question',
                            'name' => 'Apakah bisa dipakai jika router MikroTik tidak punya IP Publik Statis?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Bisa 100%! MooWiFi menyediakan fitur Auto VPN Tunneling (SSTP & WireGuard). Anda cukup meng-copy 1 baris script yang di-generate dari dasbor MooWiFi ke Terminal Winbox MikroTik Anda, dan router langsung terhubung ke sistem tanpa perlu sewa IP publik.',
                            ],
                        ],
                        [
                            '@type' => 'Question',
                            'name' => 'Kemana uang pembayaran tagihan internet pelanggan masuk?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Uang pembayaran tagihan 100% langsung masuk ke rekening atau akun merchant payment gateway milik Anda sendiri (Duitku, Midtrans, Xendit, Tripay, atau rekening bank manual). MooWiFi tidak menampung uang pembayaran warga.',
                            ],
                        ],
                        [
                            '@type' => 'Question',
                            'name' => 'Apakah pendaftaran membutuhkan verifikasi email?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Ya. Demi keamanan akun dan mencegah penyalahgunaan platform, setiap pendaftar baru wajib memverifikasi kepemilikan email melalui link verifikasi resmi yang dikirimkan oleh server platform kami.',
                            ],
                        ],
                        [
                            '@type' => 'Question',
                            'name' => 'Bagaimana cara kerja Auto-Cut dan Auto-Restore MikroTik?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Jika pelanggan melewati batas jatuh tempo dan masa tenggang (grace period), sistem scheduler otomatis mengubah profil PPP Secret / Simple Queue pelanggan menjadi profil isolir. Begitu tagihan dibayar lunas, webhook gateway memicu sistem untuk langsung me-restore profil normal secara realtime.',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($schemaOrgData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
    </script>

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

        /* Generous Section Spacing & Layout Tokens (Moonbyte / MediCloud Standard) */
        .section-py {
            padding-top: 5.5rem !important;
            padding-bottom: 5.5rem !important;
        }

        @media (min-width: 992px) {
            .section-py {
                padding-top: 7.5rem !important;
                padding-bottom: 7.5rem !important;
            }
        }

        .section-heading-wrap {
            margin-bottom: 3.5rem !important;
        }

        @media (min-width: 992px) {
            .section-heading-wrap {
                margin-bottom: 5rem !important;
            }
        }

        .hero-section {
            padding-top: 5rem;
            padding-bottom: 6.5rem;
            background: radial-gradient(circle at 85% 15%, rgba(255, 141, 109, 0.14) 0%, rgba(255, 255, 255, 0) 55%);
        }

        @media (min-width: 992px) {
            .hero-section {
                padding-top: 7rem;
                padding-bottom: 8.5rem;
            }
        }

        .clients-tech-section {
            padding-top: 3.5rem !important;
            padding-bottom: 3.5rem !important;
        }

        @media (min-width: 992px) {
            .clients-tech-section {
                padding-top: 4.5rem !important;
                padding-bottom: 4.5rem !important;
            }
        }

        .stats-section {
            padding-top: 5.5rem !important;
            padding-bottom: 6rem !important;
        }

        @media (min-width: 992px) {
            .stats-section {
                padding-top: 7rem !important;
                padding-bottom: 7.5rem !important;
            }
        }

        .features-section {
            padding-top: 6rem !important;
            padding-bottom: 6.5rem !important;
        }

        @media (min-width: 992px) {
            .features-section {
                padding-top: 8rem !important;
                padding-bottom: 8.5rem !important;
            }
        }

        .how-section {
            padding-top: 6rem !important;
            padding-bottom: 6.5rem !important;
        }

        @media (min-width: 992px) {
            .how-section {
                padding-top: 8rem !important;
                padding-bottom: 8.5rem !important;
            }
        }

        .pricing-section {
            padding-top: 6rem !important;
            padding-bottom: 6.5rem !important;
        }

        @media (min-width: 992px) {
            .pricing-section {
                padding-top: 8rem !important;
                padding-bottom: 8.5rem !important;
            }
        }

        .ticket-section {
            padding-top: 6rem !important;
            padding-bottom: 6.5rem !important;
        }

        @media (min-width: 992px) {
            .ticket-section {
                padding-top: 8rem !important;
                padding-bottom: 8.5rem !important;
            }
        }

        .faq-section {
            padding-top: 6rem !important;
            padding-bottom: 7rem !important;
        }

        @media (min-width: 992px) {
            .faq-section {
                padding-top: 8rem !important;
                padding-bottom: 9rem !important;
            }
        }

        .footer-section {
            padding-top: 6rem !important;
            padding-bottom: 3.5rem !important;
        }

        @media (min-width: 992px) {
            .footer-section {
                padding-top: 7.5rem !important;
                padding-bottom: 4rem !important;
            }
        }

        .tech-badge-item {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border: 1px solid var(--mc-gray-200);
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--mc-gray-800);
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            transition: all 0.25s ease;
        }

        .tech-badge-item:hover {
            transform: translateY(-2px);
            border-color: var(--mc-orange);
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }

        /* Marquee Ticker for Payment Gateways */
        .pg-marquee-container {
            overflow: hidden;
            position: relative;
            width: 100%;
            padding: 12px 0 6px 0;
            mask-image: linear-gradient(to right, transparent 0%, black 6%, black 94%, transparent 100%);
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 6%, black 94%, transparent 100%);
        }

        .pg-marquee-track {
            display: flex;
            gap: 16px;
            width: max-content;
            animation: pg-scroll-left 25s linear infinite;
        }

        .pg-marquee-track:hover {
            animation-play-state: paused;
        }

        @keyframes pg-scroll-left {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .pg-logo-card {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid var(--mc-gray-200);
            padding: 10px 24px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 58, 67, 0.04);
            white-space: nowrap;
            transition: all 0.25s ease;
            height: 56px;
            min-width: 120px;
            cursor: default;
        }

        .pg-logo-card img, .pg-logo-card svg {
            max-height: 28px;
            max-width: 125px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .pg-logo-card:hover {
            transform: translateY(-2px);
            border-color: var(--mc-orange);
            box-shadow: 0 6px 18px rgba(0, 58, 67, 0.08);
        }

        /* Stats Cards */
        .stat-card-mc {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px 24px;
            text-align: center;
            border: 1px solid var(--mc-gray-200);
            box-shadow: 0 4px 20px rgba(0, 58, 67, 0.04);
            transition: all 0.25s ease;
            height: 100%;
        }

        .stat-card-mc:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 58, 67, 0.08);
            border-color: var(--mc-orange);
        }

        .stat-num-mc {
            font-size: 2.6rem;
            font-weight: 700;
            color: var(--mc-dark-green);
            line-height: 1.1;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        /* Feature Cards */
        .feature-card .card {
            border-radius: 22px;
            transition: all 0.25s ease;
            padding: 2.5rem 2rem !important;
            border: 1px solid var(--mc-gray-200) !important;
        }

        .feature-card .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 58, 67, 0.09) !important;
            border-color: var(--mc-orange) !important;
        }

        .feature-icon-box {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: rgba(0, 58, 67, 0.08);
            color: var(--mc-dark-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            margin-bottom: 1.5rem;
        }

        /* Pricing Cards & Switch */
        .pricing-switch-wrapper {
            display: inline-flex;
            align-items: center;
            background: #ffffff;
            border: 1px solid var(--mc-gray-200);
            border-radius: 9999px;
            padding: 5px;
            box-shadow: 0 4px 15px rgba(0, 58, 67, 0.06);
        }

        .pricing-switch-btn {
            border: none;
            background: transparent;
            padding: 8px 22px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 9999px;
            color: #6c757d;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .pricing-switch-btn.active {
            background: var(--mc-dark-green);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 58, 67, 0.25);
        }

        .pricing-card .card {
            border-radius: 20px;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            padding: 1.75rem 1.35rem !important;
            border: 1px solid var(--mc-gray-200);
        }

        .pricing-card .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px rgba(0, 58, 67, 0.1) !important;
        }

        .pricing-card.is-popular .card {
            border: 2px solid var(--mc-dark-green) !important;
            box-shadow: 0 12px 30px rgba(0, 58, 67, 0.12) !important;
        }

        [x-cloak] { display: none !important; }

        /* Mockup Glass Card */
        .hero-mockup-card {
            background: #ffffff;
            border: 1px solid var(--mc-gray-200);
            border-radius: 24px;
            box-shadow: 0 24px 60px rgba(0, 58, 67, 0.12);
            overflow: hidden;
            padding: 2.25rem !important;
        }

        /* Step Card */
        .step-card-box {
            background: var(--mc-gray-100);
            border: 1px solid var(--mc-gray-200);
            border-radius: 24px;
            padding: 3rem 2.25rem;
            height: 100%;
            transition: all 0.25s ease;
        }

        .step-card-box:hover {
            background: #ffffff;
            transform: translateY(-4px);
            box-shadow: 0 16px 36px rgba(0, 58, 67, 0.08);
            border-color: var(--mc-orange);
        }

        /* Ticket Tabs */
        .nav-pills-mc .nav-link {
            border-radius: 50px;
            padding: 10px 28px;
            font-weight: 600;
            color: var(--mc-dark-green);
            background: var(--mc-gray-100);
            border: 1px solid var(--mc-gray-200);
            margin: 0 6px;
            font-size: 0.95rem;
        }

        .nav-pills-mc .nav-link.active {
            background: var(--mc-dark-green);
            color: #ffffff;
            border-color: var(--mc-dark-green);
            box-shadow: 0 6px 18px rgba(0, 58, 67, 0.25);
        }

        /* FAQ Accordion Styling */
        .faq-item-box {
            border: 1px solid var(--mc-gray-200);
            border-radius: 20px !important;
            padding: 1.5rem 1.85rem !important;
            background: #ffffff;
            margin-bottom: 1.25rem !important;
            box-shadow: 0 4px 14px rgba(0, 58, 67, 0.03);
            transition: all 0.25s ease;
        }

        .faq-item-box:hover {
            border-color: var(--mc-orange);
            box-shadow: 0 8px 24px rgba(0, 58, 67, 0.06);
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
                    <div class="hero-mockup-card">
                        <!-- Invoice Confirmation Card -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="text-muted small fw-medium">Pembayaran Tagihan Terverifikasi</div>
                                <h3 class="h2 fw-bold text-dark mb-0 mt-1">Rp 150.000</h3>
                            </div>
                            <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.02em;">
                                <i class="fas fa-check-circle me-1"></i> LUNAS OTOMATIS
                            </span>
                        </div>

                        <!-- Clean Detail Information List -->
                        <div class="p-3.5 rounded-3 mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem;">
                            <div class="d-flex justify-content-between py-2 border-bottom small">
                                <span class="text-muted">Pelanggan</span>
                                <span class="fw-semibold text-dark">Bpk. Budi Santoso (Home-042)</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom small">
                                <span class="text-muted">Paket Langganan</span>
                                <span class="fw-semibold text-dark">Family 20 Mbps Unlimited</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom small">
                                <span class="text-muted">Metode Pembayaran</span>
                                <span class="fw-semibold text-dark">QRIS Merchant / Virtual Account</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 small">
                                <span class="text-muted">Status Isolir Jaringan</span>
                                <span class="fw-semibold text-success"><i class="fas fa-wifi me-1"></i> Pulih Otomatis (Aktif)</span>
                            </div>
                        </div>

                        <!-- Clean WhatsApp Dispatch Strip -->
                        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: rgba(5, 150, 105, 0.06); border: 1px solid rgba(5, 150, 105, 0.16);">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; background: #25D366; flex-shrink: 0;">
                                <i class="fab fa-whatsapp fs-5"></i>
                            </div>
                            <div class="small">
                                <div class="fw-semibold text-dark">Struk Pembayaran Terkirim ke WhatsApp</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Pesan konfirmasi lunas &amp; akses aktif langsung terkirim ke nomor pelanggan.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         TECH STACK LOGOS & PAYMENT GATEWAY MARQUEE
    ============================================================ -->
    <section class="bg-light clients-tech-section border-top border-bottom overflow-hidden">
        <div class="container mb-4">
            <div class="row align-items-center gy-3">
                <div class="col-xl-5">
                    <h5 class="mb-1 text-dark fw-bold">Ekosistem &amp; Integrasi Payment Gateway</h5>
                    <p class="mb-0 small text-muted">Mendukung koneksi langsung MikroTik RouterOS API, Auto-VPN Tunneling CGNAT, serta multi payment gateway otomatis.</p>
                </div>
                <div class="col-xl-7">
                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-xl-end">
                        <div class="tech-badge-item"><i class="fas fa-network-wired text-primary"></i> MikroTik RouterOS API</div>
                        <div class="tech-badge-item"><i class="fas fa-lock text-success"></i> SSTP / WireGuard VPN</div>
                        <div class="tech-badge-item"><i class="fab fa-whatsapp text-success"></i> Fonnte &amp; Wablas WhatsApp</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Continuous Smooth Moving PG Marquee (Right to Left) with Official Brand Logos -->
        <div class="pg-marquee-container">
            <div class="pg-marquee-track">
                <!-- Set 1 -->
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/duitku.png') }}" alt="Duitku Payment Gateway" title="Duitku">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/midtrans.png') }}" alt="Midtrans" title="Midtrans Payment Gateway">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/xendit.svg') }}" alt="Xendit" title="Xendit Payment Gateway">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/tripay.png') }}" alt="Tripay" title="Tripay Payment Gateway">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/qris.png') }}" alt="QRIS" title="QRIS Nasional">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/bca.png') }}" alt="BCA" title="BCA Virtual Account">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/mandiri.png') }}" alt="Mandiri" title="Bank Mandiri">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/bri.png') }}" alt="BRI" title="Bank Rakyat Indonesia (BRIVA)">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/bni.png') }}" alt="BNI" title="Bank Negara Indonesia">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/dana.png') }}" alt="DANA" title="DANA E-Wallet">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/gopay.png') }}" alt="GoPay" title="GoPay">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/shopeepay.png') }}" alt="ShopeePay" title="ShopeePay">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/indomaret.png') }}" alt="Indomaret" title="Indomaret Gerai Retail">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/alfamart.png') }}" alt="Alfamart" title="Alfamart Gerai Retail">
                </div>

                <!-- Set 2 (Duplicate for Seamless Infinite Marquee Loop) -->
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/duitku.png') }}" alt="Duitku Payment Gateway" title="Duitku">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/midtrans.png') }}" alt="Midtrans" title="Midtrans Payment Gateway">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/xendit.svg') }}" alt="Xendit" title="Xendit Payment Gateway">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/tripay.png') }}" alt="Tripay" title="Tripay Payment Gateway">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/qris.png') }}" alt="QRIS" title="QRIS Nasional">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/bca.png') }}" alt="BCA" title="BCA Virtual Account">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/mandiri.png') }}" alt="Mandiri" title="Bank Mandiri">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/bri.png') }}" alt="BRI" title="Bank Rakyat Indonesia (BRIVA)">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/bni.png') }}" alt="BNI" title="Bank Negara Indonesia">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/dana.png') }}" alt="DANA" title="DANA E-Wallet">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/gopay.png') }}" alt="GoPay" title="GoPay">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/shopeepay.png') }}" alt="ShopeePay" title="ShopeePay">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/indomaret.png') }}" alt="Indomaret" title="Indomaret Gerai Retail">
                </div>
                <div class="pg-logo-card">
                    <img src="{{ asset('images/gateways/alfamart.png') }}" alt="Alfamart" title="Alfamart Gerai Retail">
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         STATS STRIP
    ============================================================ -->
    <section class="stats-section bg-white">
        <div class="container">
            <div class="row g-4 g-lg-5">
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
    <section class="features-section bg-light" id="features">
        <div class="container">
            <div class="row justify-content-center text-center section-heading-wrap">
                <div class="col-xl-7">
                    <h2 class="display-6 mb-3">Fitur Lengkap untuk Pengusaha RT/RW Net &amp; ISP</h2>
                    <p class="text-muted">Semua yang Anda butuhkan untuk mengelola pelanggan, jaringan router, keuangan, dan otomasi tagihan dalam satu aplikasi.</p>
                </div>
            </div>

            <div class="row g-4 g-lg-5">
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
    <section class="how-section bg-white" id="howItWork">
        <div class="container">
            <div class="row justify-content-center text-center section-heading-wrap">
                <div class="col-xl-6">
                    <h2 class="display-6 mb-3">Cara Kerja MooWiFi dalam 3 Langkah</h2>
                    <p class="text-muted">Sangat mudah diintegrasikan dengan jaringan MikroTik yang sudah berjalan tanpa perlu merombak topologi.</p>
                </div>
            </div>

            <div class="row g-4 g-lg-5">
                <div class="col-md-4 text-center">
                    <div class="step-card-box">
                        <div class="display-5 text-primary fw-bold mb-3">01</div>
                        <h3 class="h4 mb-2">Daftar Akun &amp; Verifikasi Email</h3>
                        <p class="text-muted small mb-0">Buat akun tenant RT/RW Net baru secara gratis. Verifikasi alamat email aktif Anda untuk mengaktifkan akses penuh dasbor trial 14 hari.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="step-card-box">
                        <div class="display-5 text-primary fw-bold mb-3">02</div>
                        <h3 class="h4 mb-2">Hubungkan MikroTik &amp; Gateway</h3>
                        <p class="text-muted small mb-0">Masukkan IP Publik atau gunakan script Auto-VPN kami. Atur kredensial payment gateway dan nomor WhatsApp penagihan Anda.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="step-card-box">
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
    <section class="pricing-section bg-light overflow-hidden py-5" id="pricing" x-data="{ billing: 'monthly' }">
        <div class="container py-4">
            <div class="row justify-content-center text-center section-heading-wrap mb-4">
                <div class="col-xl-8">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-semibold text-uppercase ls-md mb-2">
                        Transparent Pricing
                    </span>
                    <h2 class="display-6 fw-bold mb-3 text-dark">Investasi Untuk Pertumbuhan Bisnis</h2>
                    <p class="text-secondary fs-6 mb-4">Pilihan paket fleksibel tanpa kompromi fitur. Dirancang untuk RT/RW Net &amp; ISP yang ingin berkembang cepat dan terkelola secara otomatis.</p>

                    <!-- Toggle Bulanan / Tahunan -->
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="pricing-switch-wrapper">
                            <button type="button" 
                                    class="pricing-switch-btn" 
                                    :class="{ 'active': billing === 'monthly' }" 
                                    @click="billing = 'monthly'">
                                Bulanan
                            </button>
                            <button type="button" 
                                    class="pricing-switch-btn d-flex align-items-center gap-2" 
                                    :class="{ 'active': billing === 'yearly' }" 
                                    @click="billing = 'yearly'">
                                <span>Tahunan</span>
                                <span class="badge bg-success text-white rounded-pill px-2 py-1" style="font-size: 0.72rem;">Hemat 20%</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $primaryTiers = $plans->filter(fn($p) => in_array(strtoupper($p->code), ['FREE', 'STANDAR', 'PRO']));
                $scaleTiers = $plans->filter(fn($p) => in_array(strtoupper($p->code), ['BISNIS', 'ENTERPRISE']));
            @endphp

            <!-- Baris 1: Paket Populer & Pemula (3 Kolom Lega) -->
            <div class="row g-4 justify-content-center mb-4">
                @foreach($primaryTiers as $plan)
                    @php
                        $code = strtoupper($plan->code);
                        $isPro = ($code === 'PRO');
                        $isFree = ((float) $plan->price == 0);
                    @endphp
                    <div class="col-lg-4 col-md-6 pricing-card {{ $isPro ? 'is-popular' : '' }}">
                        <div class="card bg-white h-100 shadow-sm p-4 d-flex flex-column {{ $isPro ? 'border border-2 border-primary position-relative' : 'border border-gray-200' }}" style="border-radius: 20px;">
                            <!-- Header Badge (Inside card to never be clipped) -->
                            <div class="text-center mb-2" style="min-height: 28px;">
                                @if($isPro)
                                    <span class="badge text-white rounded-pill px-3 py-1 fw-bold shadow-sm" style="background-color: var(--mc-dark-green); font-size: 0.75rem; letter-spacing: 0.5px;">
                                        <i class="fas fa-crown me-1 text-warning"></i> BEST PERFORMANCE
                                    </span>
                                @else
                                    <span class="badge text-transparent" style="font-size: 0.75rem;">&nbsp;</span>
                                @endif
                            </div>

                            <div class="text-center">
                                <h3 class="h3 fw-bold mb-1 text-dark">{{ $plan->name }}</h3>
                                <p class="text-secondary small mb-3" style="min-height: 42px; font-size: 0.85rem; line-height: 1.4;">
                                    {{ $plan->description ?? 'Cocok untuk pemula atau skala kecil' }}
                                </p>

                                <div class="my-3 py-3 px-3 bg-light rounded-3">
                                    <!-- Monthly Price -->
                                    <div x-show="billing === 'monthly'">
                                        <div class="d-flex align-items-baseline justify-content-center">
                                            <span class="fs-1 fw-bold text-dark">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                            <span class="text-muted small ms-1">/bulan</span>
                                        </div>
                                    </div>
                                    <!-- Yearly Price (20% Discount) -->
                                    <div x-show="billing === 'yearly'" x-cloak>
                                        @if($isFree)
                                            <div class="d-flex align-items-baseline justify-content-center">
                                                <span class="fs-1 fw-bold text-dark">Rp 0</span>
                                                <span class="text-muted small ms-1">/tahun</span>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-baseline justify-content-center">
                                                <span class="fs-1 fw-bold text-dark">Rp {{ number_format($plan->monthly_equivalent_yearly, 0, ',', '.') }}</span>
                                                <span class="text-muted small ms-1">/bulan</span>
                                            </div>
                                            <div class="text-success small fw-semibold mt-1">
                                                Ditagih tahunan Rp {{ number_format($plan->yearly_price, 0, ',', '.') }} (Hemat 20%)
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <ul class="list-unstyled my-3 flex-grow-1 small lh-lg" style="font-size: 0.88rem;">
                                @if(is_array($plan->features) && count($plan->features) > 0)
                                    @foreach($plan->features as $feat)
                                        <li class="d-flex align-items-start gap-2 mb-2">
                                            <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                            <span class="text-dark">{{ $feat }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">{{ $plan->max_customers == 0 ? 'Unlimited' : number_format($plan->max_customers, 0, ',', '.') }} Pelanggan</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">{{ $plan->max_routers }} Akses Mikrotik</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">Manajemen Tugas (full akses)</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">1 Whatsapp Gateway</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">Gratis VPN</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">Server Radius</span>
                                    </li>
                                @endif
                            </ul>

                            <div class="d-grid mt-auto pt-3">
                                <a :href="'{{ route('register') }}?plan={{ $plan->code }}&plan_id={{ $plan->id }}&cycle=' + billing" 
                                   class="btn {{ $isPro ? 'btn-primary shadow-sm text-white' : 'btn-outline-primary' }} rounded-pill py-2 fw-semibold">
                                    {{ $isFree ? 'Mulai Gratis (Trial 14 Hari)' : 'Pilih Paket' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pemisah Elegan untuk Paket Skala Besar -->
            <div class="row justify-content-center text-center mt-5 mb-4">
                <div class="col-lg-7">
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 fw-semibold text-uppercase small">
                        Kapasitas Skala Besar &amp; Multi-Tower
                    </span>
                    <h3 class="h4 fw-bold mt-2 text-dark">Solusi Jaringan Berkembang &amp; ISP Regional</h3>
                    <p class="text-secondary small mb-0">Dirancang khusus untuk operator yang mengelola banyak titik router dan ekspansi wilayah.</p>
                </div>
            </div>

            <!-- Baris 2: Paket Skala Besar (2 Kolom Centered, Mewah & Lapang) -->
            <div class="row g-4 justify-content-center">
                @foreach($scaleTiers as $plan)
                    @php
                        $code = strtoupper($plan->code);
                        $isEnterprise = ($code === 'ENTERPRISE');
                    @endphp
                    <div class="col-lg-5 col-md-6 pricing-card">
                        <div class="card bg-white h-100 shadow-sm p-4 p-xl-5 d-flex flex-column border border-gray-200" style="border-radius: 20px;">
                            <div class="text-center mb-2" style="min-height: 28px;">
                                @if($isEnterprise)
                                    <span class="badge bg-dark text-white rounded-pill px-3 py-1 fw-bold shadow-sm" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                        <i class="fas fa-star me-1 text-warning"></i> ENTERPRISE GRADE
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.75rem;">
                                        HIGH CAPACITY
                                    </span>
                                @endif
                            </div>

                            <div class="text-center">
                                <h3 class="h3 fw-bold mb-1 text-dark">{{ $plan->name }}</h3>
                                <p class="text-secondary small mb-3" style="min-height: 42px; font-size: 0.85rem; line-height: 1.4;">
                                    {{ $plan->description ?? 'Solusi kapasitas tinggi untuk jaringan berkembang' }}
                                </p>

                                <div class="my-3 py-3 px-3 bg-light rounded-3">
                                    <!-- Monthly Price -->
                                    <div x-show="billing === 'monthly'">
                                        <div class="d-flex align-items-baseline justify-content-center">
                                            <span class="fs-1 fw-bold text-dark">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                            <span class="text-muted small ms-1">/bulan</span>
                                        </div>
                                    </div>
                                    <!-- Yearly Price (20% Discount) -->
                                    <div x-show="billing === 'yearly'" x-cloak>
                                        <div class="d-flex align-items-baseline justify-content-center">
                                            <span class="fs-1 fw-bold text-dark">Rp {{ number_format($plan->monthly_equivalent_yearly, 0, ',', '.') }}</span>
                                            <span class="text-muted small ms-1">/bulan</span>
                                        </div>
                                        <div class="text-success small fw-semibold mt-1">
                                            Ditagih tahunan Rp {{ number_format($plan->yearly_price, 0, ',', '.') }} (Hemat 20%)
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <ul class="list-unstyled my-3 flex-grow-1 small lh-lg" style="font-size: 0.88rem;">
                                @if(is_array($plan->features) && count($plan->features) > 0)
                                    @foreach($plan->features as $feat)
                                        <li class="d-flex align-items-start gap-2 mb-2">
                                            <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                            <span class="text-dark">{{ $feat }}</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">{{ $plan->max_customers == 0 ? 'Unlimited' : number_format($plan->max_customers, 0, ',', '.') }} Pelanggan</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">{{ $plan->max_routers }} Akses Mikrotik</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">Manajemen Tugas (full akses)</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">1 Whatsapp Gateway</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">Gratis VPN</span>
                                    </li>
                                    <li class="d-flex align-items-start gap-2 mb-2">
                                        <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                                        <span class="text-dark">Server Radius</span>
                                    </li>
                                @endif
                            </ul>

                            <div class="d-grid mt-auto pt-3">
                                <a :href="'{{ route('register') }}?plan={{ $plan->code }}&plan_id={{ $plan->id }}&cycle=' + billing" 
                                   class="btn btn-outline-primary rounded-pill py-2 fw-semibold">
                                    Pilih Paket
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Jaminan / Trust Footer -->
            <div class="text-center mt-5 pt-3">
                <div class="d-inline-flex flex-wrap align-items-center justify-content-center gap-4 text-secondary small py-2 px-4 bg-white rounded-pill shadow-sm border">
                    <span><i class="fas fa-shield-alt text-success me-1"></i> Tanpa Kontrak Mengikat</span>
                    <span><i class="fas fa-sync-alt text-primary me-1"></i> Upgrade / Downgrade Kapan Saja</span>
                    <span><i class="fas fa-clock text-info me-1"></i> Uji Coba Gratis 14 Hari</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         KOLOM TIKET BANTUAN & CEK STATUS TIKET (SUPPORT DESK)
    ============================================================ -->
    <section class="ticket-section bg-white" id="support-ticket">
        <div class="container">
            <div class="row justify-content-center text-center section-heading-wrap">
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
                            <div class="card border rounded-4 p-4 p-lg-5 shadow-sm">
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
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold">
                                        <i class="ti ti-send me-1"></i> Kirimkan Tiket Bantuan
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Tab 2: Check Ticket -->
                        <div class="tab-pane fade" id="tab-check" role="tabpanel">
                            <div class="card border rounded-4 p-4 p-lg-5 shadow-sm">
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
                                    <button type="submit" class="btn btn-outline-secondary w-100 py-3 fw-semibold">
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
    <section class="faq-section bg-light" id="faq">
        <div class="container">
            <div class="row justify-content-center text-center section-heading-wrap">
                <div class="col-xl-6">
                    <h2 class="display-6 mb-3">Pertanyaan yang Sering Diajukan</h2>
                    <p class="text-muted">Informasi teknis dan praktis seputar implementasi MooWiFi di jaringan RT/RW Net Anda.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="accordion" id="accordionMooWiFi">
                        <!-- FAQ 1 -->
                        <div class="faq-item-box">
                            <h3 class="h5 mb-0">
                                <a href="#" class="text-reset d-flex justify-content-between align-items-center text-decoration-none" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    <span>Apakah bisa dipakai jika router MikroTik tidak punya IP Publik Statis?</span>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </a>
                            </h3>
                            <div id="faq1" class="collapse show" data-bs-parent="#accordionMooWiFi">
                                <div class="mt-3 text-muted small pt-3 border-top lh-lg">
                                    Bisa 100%! MooWiFi menyediakan fitur <strong>Auto VPN Tunneling (SSTP &amp; WireGuard)</strong>. Anda cukup meng-copy 1 baris script yang di-generate dari dasbor MooWiFi ke Terminal Winbox MikroTik Anda, dan router langsung terhubung ke sistem tanpa perlu sewa IP publik.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="faq-item-box">
                            <h3 class="h5 mb-0">
                                <a href="#" class="text-reset d-flex justify-content-between align-items-center text-decoration-none collapsed" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    <span>Kemana uang pembayaran tagihan internet pelanggan masuk?</span>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </a>
                            </h3>
                            <div id="faq2" class="collapse" data-bs-parent="#accordionMooWiFi">
                                <div class="mt-3 text-muted small pt-3 border-top lh-lg">
                                    Uang pembayaran tagihan <strong>100% langsung masuk ke rekening atau akun merchant payment gateway milik Anda sendiri</strong> (Duitku, Midtrans, Xendit, Tripay, atau rekening bank manual). MooWiFi tidak menampung uang pembayaran warga.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="faq-item-box">
                            <h3 class="h5 mb-0">
                                <a href="#" class="text-reset d-flex justify-content-between align-items-center text-decoration-none collapsed" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    <span>Apakah pendaftaran membutuhkan verifikasi email?</span>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </a>
                            </h3>
                            <div id="faq3" class="collapse" data-bs-parent="#accordionMooWiFi">
                                <div class="mt-3 text-muted small pt-3 border-top lh-lg">
                                    Ya. Demi keamanan akun dan mencegah penyalahgunaan platform, setiap pendaftar baru wajib memverifikasi kepemilikan email melalui link verifikasi resmi yang dikirimkan oleh server platform kami.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="faq-item-box">
                            <h3 class="h5 mb-0">
                                <a href="#" class="text-reset d-flex justify-content-between align-items-center text-decoration-none collapsed" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    <span>Bagaimana cara kerja Auto-Cut dan Auto-Restore MikroTik?</span>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </a>
                            </h3>
                            <div id="faq4" class="collapse" data-bs-parent="#accordionMooWiFi">
                                <div class="mt-3 text-muted small pt-3 border-top lh-lg">
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
    <footer class="bg-secondary footer-section text-white-50">
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

            <div class="border-top py-4 mt-5 small border-opacity-10 border-white text-white-50 text-center text-md-start">
                <p class="mb-0">&copy; {{ date('Y') }} MooWiFi Platform. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Alpine.js for interactive switches -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</body>
</html>
