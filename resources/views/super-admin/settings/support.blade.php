@extends('layouts.super-admin')

@section('title', 'Layanan Bantuan & Tiket')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Helpdesk Platform</div>
                <h2 class="page-title">Tiket Bantuan & Dukungan Teknis Tenant</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tiket Kendala dari Pemilik RT/RW Net</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>ID Tiket</th>
                            <th>Tenant Pelapor</th>
                            <th>Subjek Masalah</th>
                            <th>Prioritas</th>
                            <th>Status Tiket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-monospace small">#TCK-00102</td>
                            <td>
                                <div class="fw-bold">BudiNet Nusantara</div>
                                <div class="text-secondary small">budi@budinet.id</div>
                            </td>
                            <td>Bantuan konfigurasi Auto-VPN SSTP MikroTik di balik modem IndiHome</td>
                            <td><span class="badge bg-warning-lt">SEDANG</span></td>
                            <td><span class="badge bg-green-lt">TERJAWAB</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
