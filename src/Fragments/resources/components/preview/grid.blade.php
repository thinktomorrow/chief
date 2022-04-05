@props([
    'model' => null,
    'columns' => 2
])

@php
    $fragments = getFragments($model);
@endphp

@if(count($fragments) > 0 && $slot->isEmpty())
    <x-chief-fragments::preview.row {{ $attributes }}>
        @forelse ($fragments as $fragment)
            <x-chief-fragments::preview.column width="{{ '1/' . $columns }}">
                <x-chief-fragments::preview.card>
                    {!! $fragment->renderAdminFragment($model, $loop, ['nested' => true]) !!}
                </x-chief-fragments::preview.card>
            </x-chief-fragments::preview.column>
        @empty
            @if($slot->isNotEmpty())
                {{ $slot }}
            @endif
        @endforelse
    </x-chief-fragments::preview.row>
@endif
