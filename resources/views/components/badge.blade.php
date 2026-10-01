@props(['status' => 'default', 'dot' => false])

@php
    $class = match(strtoupper((string) $status)) {
        'ACTIVE', 'ONLINE', 'SUCCESS', 'PAID', 'APPROVED' => 'bg-green-lt text-green',
        'UNPAID', 'WAITING_VERIFICATION', 'PENDING', 'TRIAL' => 'bg-yellow-lt text-yellow',
        'OVERDUE' => 'bg-orange-lt text-orange',
        'ISOLATED', 'FAILED', 'REJECTED', 'OFFLINE', 'SUSPENDED', 'CANCELLED' => 'bg-red-lt text-red',
        'DIRECT' => 'bg-blue-lt text-blue',
        'VPN_TUNNEL' => 'bg-secondary-lt text-secondary',
        default => 'bg-secondary-lt text-secondary',
    };
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $class]) }}>
    @if($dot)
        <span class="badge-dot {{ str_contains($class, 'green') ? 'bg-green' : (str_contains($class, 'red') ? 'bg-red' : (str_contains($class, 'yellow') ? 'bg-yellow' : 'bg-secondary')) }} me-1"></span>
    @endif
    {{ $slot->isEmpty() ? $status : $slot }}
</span>
