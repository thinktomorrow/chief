@props([
    'prepend' => null,
    'append' => null,
])

<div class="flex">
    @if($prepend)
        <div class="inline-flex items-center border border-r-0 bg-primary-50 border-grey-300 rounded-l-md shrink-0">
            <span class="font-medium text-primary-500 px-3 py-2.5"> {!! $prepend !!} </span>
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
        <div class="inline-flex items-center border border-l-0 bg-primary-50 border-grey-300 rounded-r-md shrink-0">
            <span class="font-medium text-primary-500 px-3 py-2.5"> {!! $append !!} </span>
        </div>
    @endif
</div>
