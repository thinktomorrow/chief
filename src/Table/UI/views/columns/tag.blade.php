@php
    $threshold = 3;
    $items = $getItems();
    $count = count($items);
@endphp

<div x-data="{ isShowingMore: false }" class="flex max-w-80 flex-wrap items-start gap-1">
    @foreach ($items as $item)
        @php
            $index = $count === $threshold ? $loop->index : $loop->iteration;
        @endphp

        <x-chief-tags::tag
            color="{{ $item->getColor() ?: '' }}"
            size="xs"
            x-show="{{ $index >= $threshold ? 'isShowingMore' : 'true' }}"
        >
            {{ $item->getValue() }}
        </x-chief-tags::tag>
    @endforeach

    @if ($count > $threshold)
        <button type="button" x-on:click="isShowingMore = true" x-show="!isShowingMore" class="inline-flex">
            <x-chief-tags::tag size="xs" class="hover:ring-grey-200">
                +{{ $count - $threshold + 1 }} tags
            </x-chief-tags::tag>
        </button>
    @endif
</div>
