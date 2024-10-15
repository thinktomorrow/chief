@props([
    'action',
])

@php
    if ($action->hasLink()) {
        $attributes = $attributes->merge(['href' => $action->getLink(), 'title' => $action->getLabel()]);
    }

    if ($action->getVariant()) {
        $attributes = $attributes->filter(fn ($value, $key) => $key !== 'variant')->merge(['variant' => $action->getVariant()]);
    }
@endphp

<x-chief-table::button :attributes="$attributes">
    {!! $action->getPrependIcon() !!}

    @if ($action->getLabel())
        <span>{{ $action->getLabel() }}</span>
    @endif

    {!! $action->getAppendIcon() !!}
</x-chief-table::button>
