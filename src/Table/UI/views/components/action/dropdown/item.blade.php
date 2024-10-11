@props([
    'action',
])

<x-chief::dialog.dropdown.item
    :attributes="$action->hasLink() ? $attributes->merge(['href' => $action->getLink(), 'title' => $action->getLabel()]) : $attributes"
>
    {!! $action->getPrependIcon() !!}

    @if ($action->getLabel())
        <span>{{ $action->getLabel() }}</span>
    @endif

    {!! $action->getAppendIcon() !!}
</x-chief::dialog.dropdown.item>
