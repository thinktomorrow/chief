@if ($hasLink())
    <a href="{{ $getLink() }}" title="{{ $getDescription() }}">
        <x-chief-table::button size="xs">{{ $getLabel() }}</x-chief-table::button>
    </a>
@else
    <button wire:click="applyAction('{{ $getKey() }}')" title="{{ $getDescription() }}">
        <x-chief-table::button size="xs">{{ $getLabel() }}</x-chief-table::button>
    </button>
@endif
