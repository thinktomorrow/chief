<div class="flex max-w-80 flex-wrap items-start gap-1">
    @foreach ($getItems() as $item)
        @if ($item->hasLink())
            <a href="{{ $item->getLink() }}" title="{{ $item->getValue() }}">
                <x-chief-table::badge size="xs" :variant="$item->getVariant()">
                    {!! $item->getPrependIcon() !!}
                    {{ $item->getValue() }}
                    {!! $item->getAppendIcon() !!}
                </x-chief-table::badge>
            </a>
        @else
            <x-chief-table::badge size="xs" :variant="$item->getVariant()">
                {!! $item->getPrependIcon() !!}
                {{ $item->getValue() }}
                {!! $item->getAppendIcon() !!}
            </x-chief-table::badge>
        @endif
    @endforeach
</div>
