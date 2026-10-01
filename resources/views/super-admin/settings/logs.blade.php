@extends('layouts.super-admin')

@section('title', 'System Logs & Audit Trail')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Keamanan & Audit</div>
                <h2 class="page-title">Log Sistem & Jejak Aktivitas Platform</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Kategori Event</th>
                            <th>Keterangan Aktivitas</th>
                            <th>Eksekutor</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $l)
                            <tr>
                                <td class="text-secondary small font-monospace">{{ $l->created_at->format('d/m/Y H:i:s') }}</td>
                                <td><span class="badge bg-purple-lt">{{ $l->event }}</span></td>
                                <td>{{ $l->description }}</td>
                                <td class="text-secondary small">{{ $l->user->name ?? 'System Auto' }}</td>
                                <td class="font-monospace small text-secondary">{{ $l->ip_address ?? '127.0.0.1' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <x-empty-state title="Belum Ada Log" subtitle="Seluruh aktivitas sensitif platform akan tercatat secara permanen di sini." />
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
