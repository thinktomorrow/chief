@if($hasLink())
    <a href="{{ $getLink() }}" title="{{ $getDescription() }}">
        <x-chief-table-new::button size="sm">{{ $getLabel() }}</x-chief-table-new::button>
    </a>
@else
    <button wire:click="applyRowActionEffect('{{ $getKey() }}')" title="{{ $getDescription() }}">
        <x-chief-table-new::button size="sm">{{ $getLabel() }}</x-chief-table-new::button>
    </button>
@endif
