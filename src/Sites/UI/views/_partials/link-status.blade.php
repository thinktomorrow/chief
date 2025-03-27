<div class="relative my-1 flex size-4 items-center justify-center p-1">
    <div
        @class([
            'absolute inset-0 animate-pulse rounded-full',
            match ($site->status->value) {
                'online' => 'bg-green-100',
                'offline' => 'bg-grey-100',
                'none' => 'bg-grey-100',
            },
        ])
    ></div>

    <svg
        @class([
            'relative size-2',
            match ($site->status->value) {
                'online' => 'fill-green-500',
                'offline' => 'fill-grey-400',
                'none' => 'fill-grey-400',
            },
        ])
        viewBox="0 0 6 6"
        aria-hidden="true"
    >
        <circle cx="3" cy="3" r="3" />
    </svg>
</div>
