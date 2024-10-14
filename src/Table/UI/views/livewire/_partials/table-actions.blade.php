<div class="mt-2 flex items-start justify-between gap-4">
    <div class="flex items-start gap-2">
        @foreach ($this->getPrimaryFilters() as $filter)
            <div data-filter-key="{{ $filter->getKey() }}">
                {!! $filter->render() !!}
            </div>
        @endforeach
    </div>

    <div class="ml-auto flex items-center justify-end gap-2">

        @foreach ($this->getSecondaryActions() as $action)
            <x-chief-table::action.button :action="$action" size="base" variant="secondary" />
        @endforeach

        @if (count($this->getTertiaryActions()) > 0)
            <x-chief-table::button
                x-on:click="$dispatch('open-dialog', { 'id': 'table-tertiary-actions' })"
                variant="secondary"
            >
                <span>Meer acties</span>
                <x-chief::icon.arrow-down />
            </x-chief-table::button>

            <x-chief::dialog.dropdown id="table-tertiary-actions" placement="bottom-start">
                @foreach ($this->getTertiaryActions() as $action)
                    <x-chief-table::action.dropdown.item :action="$action" />
                @endforeach
            </x-chief::dialog.dropdown>
        @endif

        @foreach ($this->getPrimaryActions() as $action)
            <x-chief-table::action.button :action="$action" size="base" variant="primary" />
        @endforeach
    </div>
</div>
