@props([
    'size' => 'base',
])

<span
    @class([
        'bui-btn bg-white text-grey-500 shadow ring-1 ring-grey-200 focus-within:text-grey-800 focus-within:ring-grey-300 hover:ring-grey-300',
        'bui-btn-base' => $size === 'base',
        'bui-btn-sm' => $size === 'sm',
        'bui-btn-xs' => $size === 'xs',
    ])
>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path
            fill-rule="evenodd"
            d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
            clip-rule="evenodd"
        />
    </svg>

    <span class="bui-btn-content text-grey-800 *:placeholder:text-grey-500">
        {{ $slot }}
    </span>
</span>
