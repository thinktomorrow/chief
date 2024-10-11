@php
    $dropdownId = 'table-hidden-row-actions-' . $this->getRowKey($item);

    $visibleRowActions = $this->getVisibleRowActions($item);
    $hiddenRowActions = $this->getHiddenRowActions($item);
@endphp

<div class="flex min-h-6 items-center justify-end gap-1.5">
    @foreach ($visibleRowActions as $action)
        @if ($action->hasLink())
            <a href="{{ $action->getLink() }}" title="{{ $action->getLabel() }}">
                <x-chief-table::button size="xs" color="white">
                    {!! $action->getPrependIcon() !!}

                    @if ($action->getLabel())
                        <span>{{ $action->getLabel() }}</span>
                    @endif

                    {!! $action->getAppendIcon() !!}
                </x-chief-table::button>
            </a>
        @else
            <button wire:click="applyRowAction('{{ $action->getKey() }}', '{{ $item->modelReference()->getShort() }}')" title="{{ $action->getLabel() }}">
                <x-chief-table::button size="xs" color="white">
                    {!! $action->getPrependIcon() !!}

                    @if ($action->getLabel())
                        <span>{{ $action->getLabel() }}</span>
                    @endif

                    {!! $action->getAppendIcon() !!}
                </x-chief-table::button>
            </button>
        @endif
    @endforeach
</div>

@if (count($hiddenRowActions) > 0)
    <button type="button" x-on:click="$dispatch('open-dialog', { 'id': '{{ $dropdownId }}' })">
        <x-chief-table::button color="white" size="xs">
            <x-chief::icon.more-vertical-circle />
        </x-chief-table::button>
    </button>

    <x-chief::dialog.dropdown id="{{ $dropdownId }}" placement="bottom-end">
        @foreach ($hiddenRowActions as $action)
            @if($action->hasLink())
                <a href="{{ $action->getLink() }}" title="{{ $action->getDescription() }}">
                    <x-chief-table::button size="sm">{{ $action->getLabel() }}</x-chief-table::button>
                </a>
            @else
                <button wire:click="applyRowAction('{{ $action->getKey() }}', '{{ $item->modelReference()->getShort() }}')" title="{{ $action->getDescription() }}">
                    <x-chief-table::button size="sm">{{ $action->getLabel() }}</x-chief-table::button>
                </button>
            @endif
        @endforeach
    </x-chief::dialog.dropdown>
@endif
