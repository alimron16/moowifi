@props(['label', 'value', 'icon' => 'ti ti-activity', 'color' => 'primary', 'description' => null])

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="subheader">{{ $label }}</div>
            <div class="ms-auto lh-1">
                <span class="avatar avatar-sm bg-{{ $color }}-lt text-{{ $color }}">
                    <i class="{{ $icon }} fs-2"></i>
                </span>
            </div>
        </div>
        <div class="fs-2 fw-bold mb-0 mt-2 text-dark">{{ $value }}</div>
        @if($description)
            <div class="text-secondary small mt-1">{{ $description }}</div>
        @endif
    </div>
</div>
