@props(['label', 'value', 'icon' => 'ti ti-activity', 'color' => 'primary', 'description' => null])

<div class="card">
    <div class="card-body p-2 p-md-3">
        <div class="d-flex align-items-center">
            <div class="subheader text-truncate">{{ $label }}</div>
            <div class="ms-auto lh-1 d-none d-sm-block">
                <span class="avatar avatar-sm bg-{{ $color }}-lt text-{{ $color }}">
                    <i class="{{ $icon }} fs-2"></i>
                </span>
            </div>
        </div>
        <div class="fs-2 fw-bold mb-0 mt-1 mt-md-2 text-dark metric-value-mobile">{{ $value }}</div>
        @if($description)
            <div class="text-secondary small mt-1" style="font-size: 0.75rem;">{{ $description }}</div>
        @endif
    </div>
</div>
