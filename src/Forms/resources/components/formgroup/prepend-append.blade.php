@props([
    'prepend' => null,
    'append' => null,
])

<div class="flex">
    @if($prepend)
        <div class="prepend-to-input">
            {!! $prepend !!}
        </div>
    @endif

    <div class="w-full {{ $prepend ? 'with-prepend' : null }} {{ $append ? 'with-append' : null }}">
        {{ $slot }}
    </div>

    @if($append)
        <div class="append-to-input">
            {!! $append !!}
        </div>
    @endif
</div>
