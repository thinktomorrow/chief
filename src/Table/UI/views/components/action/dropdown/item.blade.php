@props([
    'action',
    'item',
])

@if ($action->hasLink())
    <a href="{{ $action->getLink() }}" title="{{ $action->getDescription() }}">
        <x-chief::dialog.dropdown.item {{ $attributes }}>
            {!! $action->getPrependIcon() !!}
            @if ($action->getLabel())
                <span>{{ $action->getLabel() }}</span>
            @endif

            {!! $action->getAppendIcon() !!}
        </x-chief::dialog.dropdown.item>
    </a>
@else
    <button
        wire:click="applyRowAction('{{ $action->getKey() }}', '{{ $item->modelReference()->getShort() }}')"
        title="{{ $action->getDescription() }}"
    >
        <x-chief::dialog.dropdown.item {{ $attributes }}>
            {!! $action->getPrependIcon() !!}
            @if ($action->getLabel())
                <span>{{ $action->getLabel() }}</span>
            @endif

            {!! $action->getAppendIcon() !!}
        </x-chief::dialog.dropdown.item>
    </button>
@endif
