@props([
    'action',
    'item',
])

@if ($action->hasLink())
    <a href="{{ $action->getLink() }}" title="{{ $action->getLabel() }}">
        <x-chief-table::button {{ $attributes }}>
            {!! $action->getPrependIcon() !!}

            @if ($action->getLabel())
                <span>{{ $action->getLabel() }}</span>
            @endif

            {!! $action->getAppendIcon() !!}
        </x-chief-table::button>
    </a>
@else
    <button
        wire:click="applyRowAction('{{ $action->getKey() }}', '{{ $item->modelReference()->getShort() }}')"
        title="{{ $action->getLabel() }}"
    >
        <x-chief-table::button {{ $attributes }}>
            {!! $action->getPrependIcon() !!}

            @if ($action->getLabel())
                <span>{{ $action->getLabel() }}</span>
            @endif

            {!! $action->getAppendIcon() !!}
        </x-chief-table::button>
    </button>
@endif
