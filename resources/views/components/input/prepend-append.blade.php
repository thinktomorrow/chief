@props([
    'prepend' => null,
    'append' => null,
])

<div class="flex">
    @if ($prepend)
        <div class="form-input-prepend">
            <span> {!! $prepend !!} </span>
        </div>
    @endif

    <div @class([
        'w-full',
        '[&>*]:form-input-with-prepend' => $prepend,
        '[&>*]:form-input-with-append' => $append
    ])>
        {{ $slot }}
    </div>

    @if ($append)
        <div class="form-input-append">
            <span> {!! $append !!} </span>
        </div>
    @endif
</div>
