@extends('layouts.tenant')

@section('title', 'Log Perangkat & Jaringan MikroTik')

@section('tenant-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Riwayat & Diagnostik</div>
                <h2 class="page-title">Log Operasional Jaringan & MikroTik</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('tenant.routers.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Router
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aktivitas Sistem & MikroTik</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Waktu Kejadian</th>
                            <th>Kategori Event</th>
                            <th>Keterangan Aktivitas</th>
                            <th>Pelaksana / Eksekutor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-secondary small font-monospace">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td>
                                    @if(str_contains($log->event, 'ISOLAT'))
                                        <span class="badge bg-danger-lt">ISOLIR</span>
                                    @elseif(str_contains($log->event, 'RESTORE'))
                                        <span class="badge bg-success-lt">RESTORE</span>
                                    @elseif(str_contains($log->event, 'DISCONNECT'))
                                        <span class="badge bg-warning-lt">DISCONNECT</span>
                                    @else
                                        <span class="badge bg-blue-lt">{{ $log->event }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $log->description }}</div>
                                </td>
                                <td class="text-secondary small">
                                    {{ $log->user->name ?? 'Sistem Otomatis (Scheduler)' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <x-empty-state 
                                        title="Belum Ada Log Tercatat" 
                                        subtitle="Aktivitas isolir, pemutusan sesi, dan sinkronisasi router akan tercatat di sini."
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="card-footer d-flex align-items-center">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
