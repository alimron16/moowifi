@props(['title' => 'Belum Ada Data', 'subtitle' => 'Data untuk kategori ini belum tersedia.', 'icon' => 'ti ti-database-off', 'action' => null])

<div class="empty py-5">
    <div class="empty-icon">
        <i class="{{ $icon }} fs-1 text-secondary"></i>
    </div>
    <p class="empty-title">{{ $title }}</p>
    <p class="empty-subtitle text-secondary">
        {{ $subtitle }}
    </p>
    @if($action)
        <div class="empty-action">
            {{ $action }}
        </div>
    @endif
</div>
