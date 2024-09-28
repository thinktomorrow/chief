@if($hasLink())
    <a href="{{ $getLink() }}" title="{{ $getDescription() }}">
        <x-chief-table::button size="sm">{{ $getLabel() }}</x-chief-table::button>
    </a>
@else
    <button wire:click="applyBulkAction('{{ $getKey() }}')" title="{{ $getDescription() }}">
        <x-chief-table::button size="sm">{{ $getLabel() }}</x-chief-table::button>
    </button>
@endif
