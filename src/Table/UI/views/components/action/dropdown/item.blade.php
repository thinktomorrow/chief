@props([
    'action',
])

<x-chief::dialog.dropdown.item
    x-on:click="{{ $action->shouldCloseDialog() ? 'close()' : '' }}"
    :attributes="$action->hasLink() ? $attributes->merge(['href' => $action->getLink(), 'title' => $action->getLabel()]) : $attributes"
>
    {!! $action->getPrependIcon() !!}

    <x-chief::dialog.dropdown.item.content label="{{ $action->getLabel() }}">
        <p>{!! $action->getDescription() !!}</p>
    </x-chief::dialog.dropdown.item.content>

    {!! $action->getAppendIcon() !!}
</x-chief::dialog.dropdown.item>
