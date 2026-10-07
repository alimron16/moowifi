<!DOCTYPE html>
<html lang="id" style="scroll-behavior: smooth;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title>Syarat dan Ketentuan Pengguna - MooWiFi SaaS Platform</title>
    <meta name="title" content="Syarat dan Ketentuan Pengguna - MooWiFi SaaS Platform">
    <meta name="description" content="Syarat dan Ketentuan Penggunaan Layanan Platform SaaS MooWiFi. Menjelaskan batasan tanggung jawab, legalitas perangkat lunak, dan aturan penggunaan layanan bagi pengusaha RT/RW Net dan ISP.">
    <meta name="keywords" content="syarat ketentuan moowifi, terms of service moowifi, perjanjian lisensi saas, hukum rtrw net, batasan tanggung jawab moowifi">
    <meta name="author" content="MooWiFi Legal Team">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/syarat-dan-ketentuan') }}">

    <!-- Open Graph -->
    <meta property="og:type" content="article">
    <meta property="og:locale" content="id_ID">
    <meta property="og:url" content="{{ url('/syarat-dan-ketentuan') }}">
    <meta property="og:site_name" content="MooWiFi">
    <meta property="og:title" content="Syarat dan Ketentuan Pengguna - MooWiFi SaaS Platform">
    <meta property="og:description" content="Perjanjian hukum dan ketentuan penggunaan layanan platform software billing & manajemen jaringan MooWiFi.">
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
            --mc-danger: #DC2626;
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
            line-height: 1.7;
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

        .bg-secondary {
            background-color: var(--mc-dark-green) !important;
        }

        .terms-header {
            background: linear-gradient(135deg, rgba(0, 58, 67, 0.04) 0%, rgba(255, 141, 109, 0.08) 100%);
            border-bottom: 1px solid var(--mc-gray-200);
            padding: 60px 0 50px;
        }

        .terms-card {
            background: #ffffff;
            border: 1px solid var(--mc-gray-200);
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 58, 67, 0.04);
            padding: 40px;
        }

        .terms-clause {
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--mc-gray-200);
        }

        .terms-clause:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .clause-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--mc-dark-green);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .clause-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background-color: var(--mc-dark-green);
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .alert-legal {
            background-color: #fff8f5;
            border: 1px solid rgba(255, 141, 109, 0.4);
            border-left: 4px solid var(--mc-orange);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }

        .toc-list a {
            color: var(--mc-gray-700);
            text-decoration: none;
            display: block;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .toc-list a:hover {
            background-color: rgba(0, 58, 67, 0.06);
            color: var(--mc-dark-green);
            padding-left: 16px;
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
                    <li class="nav-item"><a href="{{ route('landing.about') }}" class="nav-link fw-semibold text-dark px-3">Tentang Kami</a></li>
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
         HEADER
    ============================================================ -->
    <header class="terms-header">
        <div class="container text-center">
            <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill fw-semibold mb-3 shadow-sm">
                <i class="ti ti-scale text-primary me-1"></i> Dokumen Perjanjian Hukum Resmi
            </span>
            <h1 class="display-5 fw-bold text-dark mb-2">Syarat dan Ketentuan Pengguna</h1>
            <p class="text-muted mx-auto mb-1" style="max-width: 700px;">
                Ketentuan Layanan Platform Perangkat Lunak SaaS MooWiFi
            </p>
            <small class="text-secondary font-monospace">Terakhir Diperbarui: 7 Oktober 2026 &bull; Berlaku Efektif untuk Seluruh Tenant</small>
        </div>
    </header>

    <!-- ============================================================
         KONTEN HUKUM LENGKAP
    ============================================================ -->
    <section class="py-5">
        <div class="container py-lg-3">
            <div class="row g-4">
                <!-- Sidebar Navigasi Pasal -->
                <div class="col-lg-4 col-xl-3 d-none d-lg-block">
                    <div class="sticky-top" style="top: 90px;">
                        <div class="card border rounded-4 p-3 shadow-sm">
                            <h6 class="fw-bold text-dark text-uppercase small px-2 mb-2">Daftar Pasal</h6>
                            <nav class="toc-list">
                                <a href="#pasal-1">1. Definisi &amp; Ruang Lingkup</a>
                                <a href="#pasal-2">2. Batasan Penyedia Software (Bukan ISP)</a>
                                <a href="#pasal-3">3. Kepatuhan &amp; Legalitas Tenant</a>
                                <a href="#pasal-4">4. Kepemilikan Dana Pelanggan</a>
                                <a href="#pasal-5">5. Otomasi Router &amp; MikroTik</a>
                                <a href="#pasal-6">6. Langganan SaaS &amp; Non-Refund</a>
                                <a href="#pasal-7">7. Perlindungan Data Pribadi</a>
                                <a href="#pasal-8">8. Pembatasan Tanggung Jawab</a>
                                <a href="#pasal-9">9. Ganti Rugi (Indemnifikasi)</a>
                                <a href="#pasal-10">10. Penangguhan &amp; Terminasi Akun</a>
                                <a href="#pasal-11">11. Hukum yang Berlaku &amp; Domisili</a>
                                <a href="#pasal-12">12. Kontak &amp; Alamat Resmi</a>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Isi Perjanjian -->
                <div class="col-lg-8 col-xl-9">
                    <div class="terms-card">
                        
                        <div class="alert-legal">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="ti ti-alert-circle text-primary fs-4"></i>
                                <strong class="text-dark">PENTING DIBACA SEBELUM MENDAFTAR:</strong>
                            </div>
                            <p class="small text-secondary mb-0">
                                Dengan mendaftar, membuat akun, mengakses, atau menggunakan platform <strong>MooWiFi</strong>, Anda (selanjutnya disebut "Pengguna" atau "Tenant") menyatakan telah membaca, memahami, dan menyetujui secara mutlak seluruh Syarat dan Ketentuan ini. Jika Anda tidak menyetujui ketentuan ini, Anda dilarang menggunakan platform MooWiFi.
                            </p>
                        </div>

                        <!-- PASAL 1 -->
                        <div class="terms-clause" id="pasal-1">
                            <div class="clause-title">
                                <span class="clause-number">1</span>
                                <span>Definisi &amp; Ruang Lingkup Layanan</span>
                            </div>
                            <ol class="text-secondary ps-3 lh-lg mb-0">
                                <li><strong>Platform MooWiFi</strong> adalah aplikasi perangkat lunak berbasis cloud (Software-as-a-Service / SaaS) yang menyediakan alat bantu digital untuk manajemen data pelanggan internet, pembuatan invoice tagihan, integrasi router MikroTik RouterOS, integrasi payment gateway, dan pengiriman notifikasi pengingat via WhatsApp/Email.</li>
                                <li><strong>Pengelola Platform</strong> adalah penyedia dan pemilik sah sistem perangkat lunak MooWiFi yang berkedudukan di Kota Tangerang Selatan, Indonesia.</li>
                                <li><strong>Tenant / Pengguna</strong> adalah individu, badan usaha, komunitas, atau pengelola jaringan (RT/RW Net / ISP lokal) yang mendaftarkan diri pada platform MooWiFi untuk menggunakan software manajemen billing.</li>
                                <li><strong>Pelanggan Akhir (End-User)</strong> adalah warga atau konsumen perorangan yang berlangganan akses internet kepada Tenant.</li>
                            </ol>
                        </div>

                        <!-- PASAL 2 -->
                        <div class="terms-clause" id="pasal-2">
                            <div class="clause-title">
                                <span class="clause-number">2</span>
                                <span>Kedudukan Penyedia Software (Bukan Penyelenggara ISP)</span>
                            </div>
                            <p class="text-secondary lh-lg mb-2">
                                <strong>MooWiFi semata-mata merupakan penyedia solusi perangkat lunak (Software-as-a-Service).</strong>
                            </p>
                            <ol class="text-secondary ps-3 lh-lg mb-0">
                                <li>MooWiFi <strong>BUKAN</strong> Penyelenggara Jasa Internet (Internet Service Provider - ISP), bukan penjual bandwidth internet, bukan operator telekomunikasi, dan tidak menyediakan koneksi fisik internet kepada pengguna maupun masyarakat.</li>
                                <li>MooWiFi tidak memiliki kontrol atas kualitas sinyal, kecepatan throughput riil, redaman kabel fiber optik, interferensi radio nirkabel, atau kestabilan koneksi internet yang didistribusikan oleh Tenant kepada Pelanggan Akhir.</li>
                            </ol>
                        </div>

                        <!-- PASAL 3 -->
                        <div class="terms-clause" id="pasal-3">
                            <div class="clause-title">
                                <span class="clause-number">3</span>
                                <span>Kepatuhan Hukum &amp; Legalitas Usaha Tenant</span>
                            </div>
                            <div class="alert bg-light border p-3 rounded-3 mb-3 small text-secondary">
                                <strong class="text-dark d-block mb-1"><i class="ti ti-shield-alert text-warning me-1"></i> Tanggung Jawab Mutlak Pengguna:</strong>
                                Setiap Tenant bertanggung jawab penuh dan mandiri atas kepatuhan regulasi telekomunikasi dan hukum yang berlaku di wilayah operasionalnya di Republik Indonesia.
                            </div>
                            <ol class="text-secondary ps-3 lh-lg mb-0">
                                <li>Tenant wajib memastikan bahwa operasional distribusi internet miliknya telah memenuhi ketentuan perundang-undangan, termasuk Undang-Undang Telekomunikasi (UU No. 36/1999), perizinan berusaha KBLI terkait, izin frekuensi spektrum radio, atau bentuk kemitraan resmi (reseller resmi berizin) dengan ISP pemegang izin Penyelenggaraan Jasa Akses Internet dari Kementerian Komunikasi dan Informatika (Kominfo).</li>
                                <li>Pengelola Platform MooWiFi dibebaskan secara mutlak dari segala bentuk keterlibatan, tuntutan hukum, denda administratif, atau sanksi pidana yang timbul akibat praktik penyediaan internet ilegal, pelanggaran hak cipta, atau sengketa wilayah operasional yang dilakukan oleh Tenant.</li>
                            </ol>
                        </div>

                        <!-- PASAL 4 -->
                        <div class="terms-clause" id="pasal-4">
                            <div class="clause-title">
                                <span class="clause-number">4</span>
                                <span>Aliran Dana &amp; Pembayaran Pelanggan Akhir</span>
                            </div>
                            <ol class="text-secondary ps-3 lh-lg mb-0">
                                <li><strong>Bukan Penampung Dana (No Escrow):</strong> Seluruh pembayaran tagihan dari Pelanggan Akhir 100% langsung masuk ke rekening bank pribadi/badan usaha milik Tenant atau akun merchant payment gateway milik Tenant sendiri (seperti Duitku, Xendit, Midtrans, atau Tripay).</li>
                                <li>MooWiFi tidak memotong persentase komisi transaksi warga, tidak menahan dana, dan tidak bertindak sebagai lembaga keuangan, dompet digital, maupun penyelenggara transfer dana bagi pelanggan Tenant.</li>
                                <li>Segala keluhan, klaim pengembalian dana (*refund*), kelebihan bayar, atau sengketa keuangan antara Tenant dan Pelanggan Akhir merupakan urusan internal Tenant yang wajib diselesaikan mandiri oleh Tenant tanpa melibatkan MooWiFi.</li>
                            </ol>
                        </div>

                        <!-- PASAL 5 -->
                        <div class="terms-clause" id="pasal-5">
                            <div class="clause-title">
                                <span class="clause-number">5</span>
                                <span>Integrasi MikroTik, Router, &amp; Automasi Jaringan</span>
                            </div>
                            <ol class="text-secondary ps-3 lh-lg mb-0">
                                <li>Fitur Auto-Cut, Auto-Restore, dan sinkronisasi profil MikroTik dijalankan oleh sistem berdasarkan data jatuh tempo dan instruksi yang dikonfigurasikan sendiri oleh Tenant.</li>
                                <li>Tenant bertanggung jawab menjaga keamanan kredensial login API router miliknya. Kredensial disimpan dalam database MooWiFi menggunakan enkripsi standar industri.</li>
                                <li>MooWiFi tidak bertanggung jawab atas kerusakan perangkat keras router, kegagalan pasokan listrik di sisi Tenant, putusnya tautan VPN akibat gangguan ISP hulu (upstream), kesalahan konfigurasi script Winbox yang dimasukkan sendiri oleh Tenant, atau downtime router di lapangan.</li>
                            </ol>
                        </div>

                        <!-- PASAL 6 -->
                        <div class="terms-clause" id="pasal-6">
                            <div class="clause-title">
                                <span class="clause-number">6</span>
                                <span>Biaya Langganan SaaS, Uji Coba, &amp; Kebijakan Non-Refund</span>
                            </div>
                            <ol class="text-secondary ps-3 lh-lg mb-0">
                                <li><strong>Masa Uji Coba (Trial):</strong> MooWiFi menyediakan masa uji coba gratis (30 hari) bagi akun baru untuk menguji fitur platform. Kuota pelanggan dan router pada masa uji coba dibatasi sesuai paket yang berlaku.</li>
                                <li><strong>Langganan Prabayar (Prepaid):</strong> Penggunaan platform setelah masa uji coba memerlukan pembayaran langganan SaaS (Bulanan atau Tahunan) sesuai paket yang dipilih (Standar, Pro, atau Enterprise).</li>
                                <li><strong>Kebijakan Tidak Dapat Dikembalikan (Non-Refundable):</strong> Seluruh biaya langganan SaaS platform yang telah dibayarkan oleh Tenant bersifat <strong>final dan tidak dapat dikembalikan (*strictly non-refundable*)</strong> untuk alasan apa pun, termasuk namun tidak terbatas pada ketidakaktifan Tenant atau pembatalan sepihak sebelum masa langganan berakhir.</li>
                                <li><strong>Penyesuaian Paket (Upgrade):</strong> Tenant dapat melakukan upgrade paket secara mandiri kapan saja. Penurunan paket (downgrade) hanya dapat diproses melalui pengajuan ke Super Admin pada akhir periode langganan aktif.</li>
                            </ol>
                        </div>

                        <!-- PASAL 7 -->
                        <div class="terms-clause" id="pasal-7">
                            <div class="clause-title">
                                <span class="clause-number">7</span>
                                <span>Kerahasiaan &amp; Perlindungan Data Pribadi (UU PDP)</span>
                            </div>
                            <ol class="text-secondary ps-3 lh-lg mb-0">
                                <li>MooWiFi tunduk pada ketentuan Undang-Undang Perlindungan Data Pribadi (UU No. 27/2022) dalam menjaga keamanan, integritas, dan kerahasiaan data yang tersimpan pada platform.</li>
                                <li>Tenant menjamin bahwa Tenant memiliki hak hukum atau persetujuan yang sah dari Pelanggan Akhir untuk memasukkan data identitas mereka (nama, nomor telepon, alamat) ke dalam sistem penagihan MooWiFi.</li>
                                <li>MooWiFi tidak akan pernah menjual, menyewakan, atau memperdagangkan basis data pelanggan Tenant kepada pihak ketiga mana pun untuk tujuan komersial.</li>
                            </ol>
                        </div>

                        <!-- PASAL 8 -->
                        <div class="terms-clause" id="pasal-8">
                            <div class="clause-title">
                                <span class="clause-number">8</span>
                                <span>Pembatasan Tanggung Jawab (Limitation of Liability)</span>
                            </div>
                            <ol class="text-secondary ps-3 lh-lg mb-0">
                                <li>Layanan platform disediakan atas dasar prinsip <em>"sebagaimana adanya" (as is)</em> dan <em>"sebagaimana tersedia" (as available)</em> tanpa jaminan kelangsungan tanpa gangguan 100%.</li>
                                <li>Sejauh diizinkan oleh hukum yang berlaku di Indonesia, Pengelola Platform MooWiFi, karyawan, pemilik, dan afiliasinya <strong>tidak bertanggung jawab</strong> atas kerugian tidak langsung, kerugian insidental, kehilangan laba bisnis, hilangnya reputasi usaha, atau kehilangan pendapatan yang dialami Tenant yang timbul dari:
                                    <ul class="ps-3 mt-1">
                                        <li>Gangguan jaringan server cloud pihak ketiga (AWS, DigitalOcean, Google Cloud, dsb) atau pemeliharaan sistem terencana;</li>
                                        <li>Keterlambatan atau kegagalan pengiriman pesan WhatsApp pihak ketiga akibat penangguhan nomor oleh Meta/WhatsApp;</li>
                                        <li>Kegagalan gateway pihak ketiga dalam memproses transaksi bank;</li>
                                        <li>Keadaan Kahar (Force Majeure) seperti bencana alam, kebakaran, huru-hara, atau kebijakan regulasi pemerintah yang membatasi layanan internet.</li>
                                    </ul>
                                </li>
                                <li>Dalam kondisi apa pun, total liabilitas maksimal MooWiFi kepada Tenant untuk seluruh tuntutan yang timbul terkait layanan platform ini dibatasi sebesar <strong>biaya langganan SaaS 1 (satu) bulan terakhir</strong> yang telah dibayarkan oleh Tenant kepada MooWiFi.</li>
                            </ol>
                        </div>

                        <!-- PASAL 9 -->
                        <div class="terms-clause" id="pasal-9">
                            <div class="clause-title">
                                <span class="clause-number">9</span>
                                <span>Ganti Rugi &amp; Pelepasan Klaim (Indemnification)</span>
                            </div>
                            <p class="text-secondary lh-lg mb-0">
                                Tenant setuju untuk membela, mengganti rugi, membebaskan, dan melepaskan MooWiFi beserta direksi, karyawan, dan pengembangnya dari dan terhadap setiap tuntutan, gugatan hukum, klaim kerugian, denda aparat penegak hukum, sanksi kementerian/regulator, dan biaya penasihat hukum yang timbul dari atau terkait dengan:
                                <br>&bull; Pelanggaran Tenant terhadap Syarat dan Ketentuan ini;
                                <br>&bull; Pelanggaran hukum atau regulasi telekomunikasi yang dilakukan oleh Tenant dalam mengoperasikan jaringan internetnya;
                                <br>&bull; Sengketa antara Tenant dengan pihak ketiga mana pun, termasuk Pelanggan Akhir dan mitra penyedia koneksi internet Tenant.
                            </p>
                        </div>

                        <!-- PASAL 10 -->
                        <div class="terms-clause" id="pasal-10">
                            <div class="clause-title">
                                <span class="clause-number">10</span>
                                <span>Penangguhan &amp; Terminasi Akun</span>
                            </div>
                            <ol class="text-secondary ps-3 lh-lg mb-0">
                                <li>MooWiFi berhak menangguhkan (suspend) atau menutup akun Tenant secara sepihak tanpa pemberitahuan sebelumnya apabila ditemukan indikasi kuat:
                                    <ul class="ps-3 mt-1">
                                        <li>Penyalahgunaan platform untuk aktivitas penipuan (fraud), phising, malware, atau spam WhatsApp massal yang merugikan publik;</li>
                                        <li>Upaya peretasan, penetrasi keamanan, atau manipulasi sistem multi-tenant platform;</li>
                                        <li>Pelanggaran berat terhadap peraturan hukum Republik Indonesia.</li>
                                    </ul>
                                </li>
                            </ol>
                        </div>

                        <!-- PASAL 11 -->
                        <div class="terms-clause" id="pasal-11">
                            <div class="clause-title">
                                <span class="clause-number">11</span>
                                <span>Hukum yang Berlaku &amp; Domisili Hukum Penyelesaian Sengketa</span>
                            </div>
                            <ol class="text-secondary ps-3 lh-lg mb-0">
                                <li>Syarat dan Ketentuan ini diatur dan ditafsirkan sepenuhnya berdasarkan hukum yang berlaku di <strong>Negara Kesatuan Republik Indonesia</strong>.</li>
                                <li>Setiap perselisihan, sengketa, atau perbedaan penafsiran yang timbul sehubungan dengan perjanjian ini akan diselesaikan secara musyawarah untuk mencapai mufakat dalam waktu 30 (tiga puluh) hari kalender.</li>
                                <li>Apabila penyelesaian musyawarah tidak tercapai, para pihak sepakat tanpa syarat untuk menyelesaikan sengketa tersebut melalui yurisdiksi eksklusif di <strong>Pengadilan Negeri Kota Tangerang Selatan</strong>.</li>
                            </ol>
                        </div>

                        <!-- PASAL 12 -->
                        <div class="terms-clause" id="pasal-12">
                            <div class="clause-title">
                                <span class="clause-number">12</span>
                                <span>Alamat Korespondensi &amp; Layanan Hukum</span>
                            </div>
                            <p class="text-secondary lh-lg mb-3">
                                Seluruh pemberitahuan resmi, pertanyaan hukum, atau korespondensi terkait Syarat dan Ketentuan ini dapat dialamatkan kepada:
                            </p>
                            <div class="bg-light p-4 rounded-3 border">
                                <div class="fw-bold text-dark fs-5 mb-2">MooWiFi SaaS Platform Legal Department</div>
                                <div class="d-flex align-items-start gap-2 mb-2 text-secondary">
                                    <i class="ti ti-map-pin text-primary mt-1"></i>
                                    <div>
                                        <strong>Alamat Usaha Resmi:</strong><br>
                                        Gang Mahi Salam, RT 001/021, No. 25,<br>
                                        Kelurahan Parigi, Kecamatan Pondok Aren,<br>
                                        Kota Tangerang Selatan, Banten 15228, Indonesia
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2 text-secondary">
                                    <i class="ti ti-mail text-primary"></i>
                                    <div><strong>Email Resmi:</strong> <a href="mailto:support@moowifi.id" class="text-primary text-decoration-none">support@moowifi.id</a> / <a href="mailto:legal@moowifi.id" class="text-primary text-decoration-none">legal@moowifi.id</a></div>
                                </div>
                                <div class="d-flex align-items-center gap-2 text-secondary">
                                    <i class="ti ti-brand-whatsapp text-primary"></i>
                                    <div><strong>Layanan Kontak:</strong> +62 812-3456-7890</div>
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
                        <li><a href="{{ route('landing.about') }}" class="text-reset text-decoration-none">Tentang Kami</a></li>
                        <li><a href="{{ route('landing.terms') }}" class="text-white fw-bold text-decoration-none">Syarat &amp; Ketentuan</a></li>
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
