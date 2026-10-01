@extends('layouts.super-admin')

@section('title', 'Platform Payment Providers')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Penerimaan Langganan Platform</div>
                <h2 class="page-title">Payment Provider Platform</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">Provider Payment Gateway Platform</h3>
            </div>
            <div class="card-body">
                <p class="text-secondary">
                    Gateway ini digunakan oleh Super Admin untuk menerima pembayaran invoice <strong>langganan platform bulanan/tahunan</strong> dari para pemilik RT/RW Net ke rekening platform.
                </p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold">Duitku Platform Gateway</span>
                                <span class="badge bg-green-lt">AKTIF</span>
                            </div>
                            <div class="small text-secondary mb-2">Mendukung Virtual Account BCA, BRI, Mandiri, BNI, Permata, QRIS, & E-Wallet.</div>
                            <div class="font-monospace small text-muted">Merchant Code: D12345***</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold">Midtrans Snap Platform</span>
                                <span class="badge bg-secondary-lt">TERSEDIA</span>
                            </div>
                            <div class="small text-secondary mb-2">Alternatif kanal pembayaran kartu kredit, GoPay, ShopeePay, & QRIS.</div>
                            <div class="font-monospace small text-muted">Server Key: SB-Mid-server-***</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
