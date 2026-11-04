@php
    $threshold = 3;
    // Filter out items that don't have a value for the current locale
    $items = $getItems()->filter(fn ($item) => $item->getValue($getLocale()));
    $count = count($items);
@endphp

@if ($count > 0)
    <div x-data="{ isShowingMore: false }" class="flex items-start gap-1">
        @foreach ($items as $item)
            @php
                $index = $count === $threshold ? $loop->index : $loop->iteration;
            @endphp

            <x-chief::tag
                color="{{ $item->getColor() ?: '' }}"
                size="xs"
                x-show="{{ $index >= $threshold ? 'isShowingMore' : 'true' }}"
            >
                {{ $item->getValue($getLocale()) }}
            </x-chief::tag>
        @endforeach

        @if ($count > $threshold)
            <button type="button" x-on:click="isShowingMore = true" x-show="!isShowingMore" class="inline-flex">
                <x-chief::tag size="xs" class="hover:ring-grey-200">
                    +{{ $count - $threshold + 1 }} tags
                </x-chief::tag>
            </button>
        @endif
    </div>
@endif
