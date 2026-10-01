@props(['title' => null, 'actions' => null])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if($title || $actions)
        <div class="card-header">
            @if($title)
                <h3 class="card-title">{{ $title }}</h3>
            @endif
            @if($actions)
                <div class="card-actions">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
