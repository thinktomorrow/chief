@props([
    'label' => null,
    'value' => null,
    'size' => 'base',
])

<span
    @class([
        'bui-btn bg-white text-grey-800 shadow ring-1 ring-grey-200 hover:ring-grey-300',
        'bui-btn-base' => $size === 'base',
        'bui-btn-sm' => $size === 'sm',
        'bui-btn-xs' => $size === 'xs',
    ])
>
    <span class="bui-btn-content inline-flex items-start gap-1">
        <span>
            {{ $label }}
        </span>

        @if ($value)
            <span class="text-grey-200">|</span>

            <span class="text-nowrap text-primary-500">{{ $value }}</span>
        @endif
    </span>

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path
            fill-rule="evenodd"
            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
            clip-rule="evenodd"
        />
    </svg>
</span>
