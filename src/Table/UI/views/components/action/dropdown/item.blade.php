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

<x-chief::dialog.dropdown.item
    :attributes="$attributes"
    x-on:click="{{ $action->shouldCloseDialog() ? 'close()' : '' }}"
>
    {!! $action->getPrependIcon() !!}

    <x-chief::dialog.dropdown.item.content label="{{ $action->getLabel() }}">
        <p>{!! $action->getDescription() !!}</p>
    </x-chief::dialog.dropdown.item.content>

    {!! $action->getAppendIcon() !!}
</x-chief::dialog.dropdown.item>
