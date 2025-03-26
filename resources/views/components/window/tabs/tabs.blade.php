@props(['actions' => null])

<div {{ $attributes->class('flex items-end justify-between gap-2') }}>
    @if ($slot->isNotEmpty())
        <div
            @class([
                'flex items-end overflow-x-auto',
                '[&>[data-slot=inactive-tab]+[data-slot=inactive-tab]_[data-slot=tab-label]]:border-l-grey-200',
            ])
        >
            {{ $slot }}
        </div>
    @endif

    @if ($actions)
        <div {{ $actions->attributes->class('mb-2 ml-auto flex shrink-0 items-start justify-end') }}>
            {{ $actions }}
        </div>
    @endif
</div>
