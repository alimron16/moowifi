@props(['type' => 'info', 'dismissible' => true])

@php
    $class = match($type) {
        'success' => 'alert-success',
        'danger', 'error' => 'alert-danger',
        'warning' => 'alert-warning',
        default => 'alert-info',
    };

    $icon = match($type) {
        'success' => 'ti ti-check',
        'danger', 'error' => 'ti ti-alert-circle',
        'warning' => 'ti ti-alert-triangle',
        default => 'ti ti-info-circle',
    };
@endphp

<div {{ $attributes->merge(['class' => 'alert ' . $class . ($dismissible ? ' alert-dismissible' : '')]) }} role="alert">
    <div class="d-flex">
        <div>
            <i class="{{ $icon }} alert-icon fs-2"></i>
        </div>
        <div>
            {{ $slot }}
        </div>
    </div>
    @if($dismissible)
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    @endif
</div>
