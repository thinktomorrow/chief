@props([
    'model' => null,
    'columns' => 2,
    'threshold' => 6
])

@php
    $items = collect($model->getFragments()->all());
    $count = count($items);

    if($count > $threshold) {
        $items = $items->slice(0, $threshold - 1);
    }
@endphp

@if($count > 0 && $slot->isEmpty())
    <x-chief-fragments::preview.row {{ $attributes }}>
        @forelse ($items as $fragment)
            <x-chief-fragments::preview.column width="{{ '1/' . $columns }}">
                <x-chief-fragments::preview.card>
                    {!! $fragment->with(['nested' => true])->renderInAdmin() !!}
                </x-chief-fragments::preview.card>
            </x-chief-fragments::preview.column>
        @empty
            @if($slot->isNotEmpty())
                {{ $slot }}
            @endif
        @endforelse

        @if($count > $threshold)
            <x-chief-fragments::preview.column width="{{ '1/' . $columns }}">
                <x-chief-fragments::preview.card>
                    <x-chief-fragments::preview.text>
                        +{{ $count - ($threshold - 1) }} items
                    </x-chief-fragments::preview.text>
                </x-chief-fragments::preview.card>
            </x-chief-fragments::preview.column>
        @endif
    </x-chief-fragments::preview.row>
@endif
