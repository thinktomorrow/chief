@props([
    'sortable' => false,
    'direction' => null,
])

<th
    scope="col"
    {{ $attributes->merge(['class' => 'sticky top-0 p-3 border-b border-grey-200 bg-grey-50 whitespace-nowrap']) }}
>
    @if($sortable)
        <span class="inline-flex items-center space-x-1.5 group">
            <span>{{ $slot }}</span>

            @if($direction == 'asc')
                <svg class="w-4 h-4 transition duration-150 ease-in-out scale-0 group-hover:scale-100">
                    <use xlink:href="#icon-chevron-up"></use>
                </svg>
            @else
                <svg class="w-4 h-4 transition duration-150 ease-in-out scale-0 group-hover:scale-100">
                    <use xlink:href="#icon-chevron-down"></use>
                </svg>
            @endif
        </span>
    @else
        <span>{{ $slot }}</span>
    @endif
</th>
