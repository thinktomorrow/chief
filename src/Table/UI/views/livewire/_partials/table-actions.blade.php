<div
    class="flex items-start justify-between gap-4"
    :class="{ '*:opacity-40 *:pointer-events-none cursor-not-allowed': selection.length > 0 }"
>
    <div class="flex items-start gap-2">
        @foreach ($this->getPrimaryFilters() as $filter)
            <div data-filter-key="{{ $filter->getKey() }}">
                {!! $filter->render() !!}
            </div>
        @endforeach
    </div>

    <div class="ml-auto flex items-center justify-end gap-2">
        @foreach ($this->getSecondaryActions() as $action)
            <x-chief-table::action.button
                :action="$action"
                wire:click="applyAction('{{ $action->getKey() }}')"
                size="base"
                variant="grey"
            />
        @endforeach

        @if (count($this->getTertiaryActions()) > 0)
            <x-chief::button
                x-on:click="$dispatch('open-dialog', { 'id': 'table-tertiary-actions' })"
                variant="outline-white"
            >
                <span>Meer acties</span>
                <x-chief::icon.chevron-down />
            </x-chief::button>

            <x-chief::dialog.dropdown id="table-tertiary-actions" placement="bottom-start">
                @foreach ($this->getTertiaryActions() as $action)
                    <x-chief-table::action.dropdown.item :action="$action" variant="grey" />
                @endforeach
            </x-chief::dialog.dropdown>
        @endif

        @foreach ($this->getPrimaryActions() as $action)
            <x-chief-table::action.button :action="$action" size="base" variant="blue" />
        @endforeach
    </div>
</div>
