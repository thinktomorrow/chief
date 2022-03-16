@props([
    'prepend' => null,
    'append' => null,
])

<div class="flex">
    @if($prepend)
        <div class="prepend">
            <span> {!! $prepend !!} </span>
        </div>
    @endif

    <div @class([
        'w-full',
        'children:with-prepend' => $prepend,
        'children:with-append' => $append
    ])>
        {{ $slot }}
    </div>

    @if($append)
        <div class="append">
            <span> {!! $append !!} </span>
        </div>
    @endif
</div>
