@props ([
    'action',
])

@php
    if ($action->hasLink()) {
        $attributes = $attributes->merge(['href' => $action->getLink(), 'title' => $action->getLabel()]);
        $attributes = $attributes->filter(fn ($value, $key) => ! str_starts_with($key, 'wire:click'));

        if ($action->shouldOpenInNewTab()) {
            $attributes = $attributes->merge(['target' => '_blank', 'rel' => 'noopener']);
        }
    }

    if ($action->getVariant()) {
        $attributes = $attributes->filter(fn ($value, $key) => $key !== 'variant')->merge(['variant' => $action->getVariant()]);
    }
@endphp

<x-chief::button :attributes="$attributes">
    {!! $action->getPrependIcon() !!}

    @if ($action->getLabel())
        <span>{{ $action->getLabel() }}</span>
    @endif

    {!! $action->getAppendIcon() !!}
</x-chief::button>
