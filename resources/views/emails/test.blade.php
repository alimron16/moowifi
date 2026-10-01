<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.5; color: #1e293b; margin: 0; padding: 20px; background-color: #f8fafc; }
        .card { max-width: 580px; margin: 0 auto; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; padding: 32px; }
        .header { border-bottom: 2px solid #206bc4; padding-bottom: 16px; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; color: #206bc4; margin: 0; }
        .badge { display: inline-block; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #15803d; background: #dcfce7; border-radius: 4px; margin-top: 10px; }
        .footer { margin-top: 24px; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 16px; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1 class="title">{{ $tenant->name }}</h1>
            <div style="font-size: 13px; color: #64748b;">Pengujian Konfigurasi Email Gmail SMTP</div>
        </div>
        <p>Halo Administrator,</p>
        <p>Email ini adalah konfirmasi bahwa konfigurasi <strong>Gmail SMTP</strong> pada sistem billing RT/RW Net Anda telah berhasil terhubung dan dapat mengirimkan email secara normal.</p>
        <div>
            <span class="badge">Koneksi SMTP Berhasil</span>
        </div>
        <p style="margin-top: 20px;">Sistem Anda sekarang dapat mengirimkan faktur tagihan dan pemberitahuan langsung ke email pelanggan.</p>
        <div class="footer">
            Dikirim secara otomatis oleh MooWiFi Billing Engine &copy; {{ date('Y') }} {{ $tenant->name }}.
        </div>
    </div>
</body>
</html>
