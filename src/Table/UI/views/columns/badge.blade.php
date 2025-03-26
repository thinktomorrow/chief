@php
    $threshold = 3;
    $count = count($getItems());
@endphp

<div x-data="{ isShowingMore: false }" class="flex max-w-80 flex-wrap items-start gap-1">
    @foreach ($getItems() as $item)
        @php
            $index = $count === $threshold ? $loop->index : $loop->iteration;
        @endphp

        @if ($item->hasLink())
            <a
                href="{{ $item->getLink() }}"
                title="{{ $item->getValue() }}"
                x-show="{{ $index >= $threshold ? 'isShowingMore' : 'true' }}"
            >
                <x-chief::badge size="xs" :variant="$item->getVariant()">
                    {{ $item->getValue() }}
                </x-chief::badge>
            </a>
        @else
            <x-chief::badge
                size="xs"
                :variant="$item->getVariant()"
                x-show="{{ $index >= $threshold ? 'isShowingMore' : 'true' }}"
            >
                {{ $item->getValue() }}
            </x-chief::badge>
        @endif
    @endforeach

    @if ($count > $threshold)
        <button type="button" x-on:click="isShowingMore = true" x-show="!isShowingMore" class="inline-flex">
            <x-chief::badge size="xs" variant="outline-transparent" class="hover:ring-grey-200">
                +{{ $count - $threshold + 1 }} items
            </x-chief::badge>
        </button>
    @endif
</div>
