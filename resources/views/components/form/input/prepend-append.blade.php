@props([
    'prepend' => null,
    'append' => null,
])

<div data-slot="control" class="flex">
    @if ($prepend)
        <div
            @class([
                'inline-flex shrink-0 items-center border border-grey-100 px-3 py-2 text-grey-500 shadow-sm',
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
                'inline-flex shrink-0 items-center border border-grey-100 px-3 py-2 text-grey-500 shadow-sm',
                'rounded-r-[0.625rem] border-l-0',
            ])
        >
            <span>{!! $append !!}</span>
        </div>
    @endif
</div>
