@if ($hasLink())
    <a href="{{ $getLink() }}" title="{{ $getDescription() }}">
        <x-chief-table-new::button size="xs">{{ $getLabel() }}</x-chief-table-new::button>
    </a>
@else
    <button wire:click="applyAction('{{ $getKey() }}')" title="{{ $getDescription() }}">
        <x-chief-table-new::button size="xs">{{ $getLabel() }}</x-chief-table-new::button>
    </button>
@endif
