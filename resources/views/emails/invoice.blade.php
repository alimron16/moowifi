<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.5; color: #1e293b; margin: 0; padding: 20px; background-color: #f8fafc; }
        .card { max-width: 580px; margin: 0 auto; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; padding: 32px; }
        .header { border-bottom: 2px solid #206bc4; padding-bottom: 16px; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; color: #206bc4; margin: 0; }
        .amount-box { background: #f1f5f9; border-radius: 6px; padding: 16px; margin: 20px 0; text-align: center; }
        .amount-label { font-size: 13px; color: #64748b; margin-bottom: 4px; }
        .amount-val { font-size: 24px; font-weight: bold; color: #0f172a; }
        .btn { display: inline-block; background: #206bc4; color: #ffffff !important; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; text-align: center; }
        .footer { margin-top: 24px; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 16px; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1 class="title">{{ $tenant->name }}</h1>
            <div style="font-size: 13px; color: #64748b;">Faktur Tagihan Layanan Internet</div>
        </div>
        <p>Yth. <strong>{{ $customer->name }}</strong>,</p>
        <p>Tagihan internet Anda untuk periode <strong>{{ $invoice->period_start->format('d/m/Y') }}</strong> s/d <strong>{{ $invoice->period_end->format('d/m/Y') }}</strong> telah diterbitkan dengan rincian berikut:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 13px;">
            <tr>
                <td style="padding: 6px 0; color: #64748b;">Nomor Tagihan:</td>
                <td style="padding: 6px 0; font-weight: bold; text-align: right;">{{ $invoice->invoice_number }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #64748b;">Tanggal Jatuh Tempo:</td>
                <td style="padding: 6px 0; font-weight: bold; color: #dc2626; text-align: right;">{{ $invoice->due_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; color: #64748b;">Paket Internet:</td>
                <td style="padding: 6px 0; text-align: right;">{{ $customer->package->name ?? 'Internet Bulanan' }}</td>
            </tr>
        </table>

        <div class="amount-box">
            <div class="amount-label">Total yang Harus Dibayar:</div>
            <div class="amount-val">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
        </div>

        <div style="text-align: center; margin: 25px 0;">
            <a href="{{ $invoice->payment_url }}" class="btn">
                Buka & Bayar Tagihan Sekarang
            </a>
        </div>

        <p style="font-size: 12px; color: #64748b;">
            Salinan faktur resmi dalam format PDF juga telah kami lampirkan pada email ini. Mohon lakukan pembayaran sebelum tanggal jatuh tempo untuk menghindari pembatasan akses internet otomatis.
        </p>

        <div class="footer">
            {{ $tenant->name }} - {{ $tenant->address ?? 'Layanan RT/RW Net' }}<br>
            Layanan Pelanggan WhatsApp: {{ $tenant->phone ?? '-' }}
        </div>
    </div>
</body>
</html>
