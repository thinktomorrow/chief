@props([
    'action',
])

<x-chief::dialog.dropdown.item
    :attributes="$action->hasLink() ? $attributes->merge(['href' => $action->getLink(), 'title' => $action->getLabel()]) : $attributes"
>
    {!! $action->getPrependIcon() !!}

    @if ($action->getLabel() || $action->getDescription())
        <div class="max-w-80 space-y-1">
            @if ($action->getLabel())
                <p>{{ $action->getLabel() }}</p>
            @endif

            @if ($action->getDescription())
                <div class="prose-format prose-editor prose-size-sm text-wrap text-grey-500">
                    <p>{!! $action->getDescription() !!}</p>
                </div>
            @endif
        </div>
    @endif

    {!! $action->getAppendIcon() !!}
</x-chief::dialog.dropdown.item>
