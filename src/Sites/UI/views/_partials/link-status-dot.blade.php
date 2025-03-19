<div class="relative my-1 flex size-4 items-center justify-center p-1">
    <div
        @class([
            'absolute inset-0 animate-pulse rounded-full',
            'bg-green-200' => $siteLink->status->value === 'online',
            'bg-grey-200' => $siteLink->status->value === 'offline',
            'bg-grey-200' => $siteLink->status->value === 'none',
        ])
    ></div>

    <svg
        @class([
            'relative size-2',
            'fill-green-500' => $siteLink->status->value === 'online',
            'fill-grey-400' => $siteLink->status->value === 'offline',
            'fill-grey-400' => $siteLink->status->value === 'none',
        ])
        viewBox="0 0 6 6"
        aria-hidden="true"
    >
        <circle cx="3" cy="3" r="3" />
    </svg>
</div>
