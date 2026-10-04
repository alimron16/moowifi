@extends('layouts.super-admin')

@section('title', 'Layanan Bantuan & Tiket')

@section('super-admin-content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Helpdesk Platform</div>
                <h2 class="page-title">Tiket Bantuan & Dukungan Teknis Platform</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('super-admin.support.index') }}" class="card card-sm text-decoration-none {{ empty($status) ? 'border-primary' : '' }}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-primary text-white avatar"><i class="ti ti-ticket"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">Semua Tiket</div>
                                <div class="text-secondary">{{ $counts['all'] }} total</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('super-admin.support.index', ['status' => 'OPEN']) }}" class="card card-sm text-decoration-none {{ $status === 'OPEN' ? 'border-danger' : '' }}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-danger text-white avatar"><i class="ti ti-clock"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">Menunggu Balasan</div>
                                <div class="text-danger fw-bold">{{ $counts['open'] }} baru</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('super-admin.support.index', ['status' => 'IN_PROGRESS']) }}" class="card card-sm text-decoration-none {{ $status === 'IN_PROGRESS' ? 'border-warning' : '' }}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar"><i class="ti ti-progress"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">Sedang Ditangani</div>
                                <div class="text-secondary">{{ $counts['in_progress'] }} tiket</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('super-admin.support.index', ['status' => 'RESOLVED']) }}" class="card card-sm text-decoration-none {{ $status === 'RESOLVED' ? 'border-success' : '' }}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-success text-white avatar"><i class="ti ti-check"></i></span>
                            </div>
                            <div class="col">
                                <div class="font-weight-medium">Terselesaikan</div>
                                <div class="text-secondary">{{ $counts['resolved'] }} selesai</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Tiket Kendala dari Pengunjung & Tenant</h3>
                <div class="card-actions">
                    <span class="text-secondary small">Menampilkan {{ $tickets->count() }} tiket</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No. Tiket</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Subjek Masalah</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $t)
                            <tr>
                                <td>
                                    <span class="badge bg-dark-lt font-monospace">{{ $t->ticket_number }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $t->name }}</div>
                                    <div class="text-secondary small">{{ $t->email }}</div>
                                    @if($t->phone)
                                        <div class="text-secondary small"><i class="ti ti-brand-whatsapp text-success me-1"></i>{{ $t->phone }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-azure-lt">{{ $t->category }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $t->subject }}</div>
                                    <div class="text-secondary small text-truncate" style="max-width: 320px;">{{ $t->message }}</div>
                                </td>
                                <td class="text-secondary small">
                                    {{ $t->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    @if($t->status === 'OPEN')
                                        <span class="badge bg-danger text-white">BARU / OPEN</span>
                                    @elseif($t->status === 'IN_PROGRESS')
                                        <span class="badge bg-warning text-dark">DIPROSES</span>
                                    @elseif($t->status === 'RESOLVED')
                                        <span class="badge bg-success text-white">TERJAWAB</span>
                                    @else
                                        <span class="badge bg-secondary text-white">DITUTUP</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-reply-{{ $t->id }}">
                                        <i class="ti ti-message-dots me-1"></i> Balas
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Reply -->
                            <div class="modal modal-blur fade" id="modal-reply-{{ $t->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('super-admin.support.reply', $t->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tangani Tiket #{{ $t->ticket_number }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card bg-body-tertiary mb-3 border-0">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <div>
                                                                <strong>{{ $t->name }}</strong> ({{ $t->email }})
                                                                @if($t->phone) &bull; {{ $t->phone }} @endif
                                                            </div>
                                                            <div class="text-secondary small">{{ $t->created_at->format('d M Y, H:i') }}</div>
                                                        </div>
                                                        <h4 class="mb-1 text-primary">{{ $t->subject }}</h4>
                                                        <p class="mb-0 text-dark" style="white-space: pre-wrap;">{{ $t->message }}</p>
                                                    </div>
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-sm-6">
                                                        <label class="form-label required">Update Status Tiket</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="OPEN" {{ $t->status === 'OPEN' ? 'selected' : '' }}>OPEN (Menunggu)</option>
                                                            <option value="IN_PROGRESS" {{ $t->status === 'IN_PROGRESS' ? 'selected' : '' }}>IN_PROGRESS (Sedang Ditangani)</option>
                                                            <option value="RESOLVED" {{ $t->status === 'RESOLVED' ? 'selected' : '' }}>RESOLVED (Selesai & Dijawab)</option>
                                                            <option value="CLOSED" {{ $t->status === 'CLOSED' ? 'selected' : '' }}>CLOSED (Ditutup)</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form-label">Kategori</label>
                                                        <input type="text" class="form-control" value="{{ $t->category }}" disabled>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label required">Tanggapan / Solusi Resmi Super Admin</label>
                                                    <textarea name="admin_reply" rows="5" class="form-control" placeholder="Tuliskan jawaban atau solusi untuk pelapor di sini..." required>{{ $t->admin_reply }}</textarea>
                                                    <small class="form-hint">Tanggapan ini dapat dilihat oleh pelapor saat mengecek status tiket pada halaman depan website.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ti ti-send me-1"></i> Simpan Jawaban &amp; Update Status
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-secondary">
                                    <i class="ti ti-ticket-off fs-1 d-block mb-2"></i>
                                    Belum ada tiket bantuan dalam kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tickets->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
