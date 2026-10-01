<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.4; padding: 20px; }
        .header { margin-bottom: 25px; }
        .header table { width: 100%; border-collapse: collapse; }
        .business-title { font-size: 22px; font-weight: bold; color: #206bc4; }
        .invoice-title { font-size: 20px; font-weight: bold; text-align: right; }
        .details-table { width: 100%; margin-bottom: 25px; border-collapse: collapse; }
        .details-table td { vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background: #f1f5f9; padding: 8px 10px; border-bottom: 2px solid #cbd5e1; text-align: left; font-size: 12px; }
        .items-table td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-box { font-size: 16px; font-weight: bold; color: #206bc4; }
        .status-stamp { display: inline-block; padding: 4px 12px; font-weight: bold; border-radius: 4px; font-size: 12px; }
        .status-paid { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .status-unpaid { background: #fef9c3; color: #a16207; border: 1px solid #fde047; }
        .footer { margin-top: 30px; font-size: 11px; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="business-title">{{ $tenant->name }}</div>
                    <div>{{ $tenant->address ?? 'Layanan RT/RW Net & Internet Lokal' }}</div>
                    <div>Telp/WA: {{ $tenant->phone ?? '-' }}</div>
                </td>
                <td class="text-right">
                    <div class="invoice-title">{{ $invoice->invoice_number }}</div>
                    <div>Status: 
                        <span class="status-stamp {{ $invoice->isPaid() ? 'status-paid' : 'status-unpaid' }}">
                            {{ $invoice->status }}
                        </span>
                    </div>
                    <div>Tanggal: {{ $invoice->issue_date->format('d/m/Y') }}</div>
                    <div>Jatuh Tempo: {{ $invoice->due_date->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="details-table">
        <tr>
            <td style="width: 55%;">
                <strong>Ditagihkan Kepada:</strong><br>
                {{ $invoice->customer->name }} ({{ $invoice->customer->customer_code }})<br>
                Telepon: {{ $invoice->customer->phone }}<br>
                Alamat: {{ $invoice->customer->address ?? '-' }}
            </td>
            <td style="width: 45%;" class="text-right">
                <strong>Periode Pemakaian:</strong><br>
                {{ $invoice->period_start->format('d/m/Y') }} s/d {{ $invoice->period_end->format('d/m/Y') }}<br>
                Paket: {{ $invoice->customer->package->name ?? '-' }} ({{ $invoice->customer->package->download_speed ?? '-' }})
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 60%;">Deskripsi Layanan</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 25%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-right" style="font-weight: bold; border-top: 2px solid #cbd5e1;">Total Tagihan:</td>
                <td class="text-right total-box" style="border-top: 2px solid #cbd5e1;">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Faktur tagihan ini diterbitkan secara otomatis oleh sistem MooWiFi Billing.<br>
        Terima kasih atas kepercayaan Anda menggunakan layanan internet kami.
    </div>
</body>
</html>
