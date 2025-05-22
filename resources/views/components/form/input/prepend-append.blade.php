@props([
    'prepend' => null,
    'append' => null,
])

<div data-slot="control" class="flex">
    @if ($prepend)
        <div
            @class([
                'border-grey-100 text-grey-500 inline-flex shrink-0 items-center border px-3 py-2 shadow-xs',
                'rounded-l-[0.625rem] border-r-0',
            ])
        >
            <span>{!! $prepend !!}</span>
        </div>
    @endif

    <div @class(['relative w-full', '[&>*]:rounded-l-none' => $prepend, '[&>*]:rounded-r-none' => $append])>
        {{ $slot }}
    </div>

    @if ($append)
        <div
            @class([
                'border-grey-100 text-grey-500 inline-flex shrink-0 items-center border px-3 py-2 shadow-xs',
                'rounded-r-[0.625rem] border-l-0',
            ])
        >
            <span>{!! $append !!}</span>
        </div>
    @endif
</div>
