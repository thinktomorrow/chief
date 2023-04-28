@props([
    'title' => null,
    'day' => null,
])

<div {{ $attributes->class('p-3 space-y-1 rounded-lg bg-grey-50 hover:bg-grey-100 transition-all duration-75 ease-in-out group') }}>
    @if($title)
        <div class="text-sm font-medium body h1-dark">
            {{ $title }}
        </div>
    @endif

    <div class="border-t divide-y divide-grey-100 border-grey-100 group-hover:border-grey-200 group-hover:divide-grey-200">
        @if(empty($day->getSlots()))
            <p class="py-1 text-sm body-dark">Gesloten</p>
        @else
            @foreach($day->getSlots() as $slot)
                <div class="py-1 text-sm body-dark">{{ $slot->getAsString() }}</div>
            @endforeach
        @endif

        @if($day->content)
            <p class="py-1 text-xs body-dark">{{ $day->content }}</p>
        @endif
    </div>
</div>
