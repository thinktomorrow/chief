<div class="flex max-w-80 flex-wrap items-start gap-1">
    @foreach ($getItems() as $badge)
        @if ($badge->hasLink())
            <a href="{{ $badge->getLink() }}" title="{{ $badge->getValue() }}">
                <x-chief-table::badge size="xs" :variant="$badge->getVariant()">
                    {{ $badge->getValue() }}
                </x-chief-table::badge>
            </a>
        @else
            <x-chief-table::badge size="xs" :variant="$badge->getVariant()">
                {{ $badge->getValue() }}
            </x-chief-table::badge>
        @endif
    @endforeach
</div>
