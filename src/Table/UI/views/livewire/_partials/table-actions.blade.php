<div class="mt-2 flex items-start justify-between gap-4">
    <div class="flex items-start gap-2">
        @foreach ($this->getMainFilters() as $filter)
            <div data-filter-key="{{ $filter->getKey() }}">
                {!! $filter->render() !!}
            </div>
        @endforeach
    </div>

    <div class="ml-auto flex items-center justify-end gap-2">
        @if (count($this->getHiddenActions()) > 0)
            <x-chief-table::button
                x-on:click="$dispatch('open-dialog', { 'id': 'table-hidden-actions' })"
                variant="secondary"
            >
                <span>Meer acties</span>
                <x-chief::icon.arrow-down />
            </x-chief-table::button>

            <x-chief::dialog.dropdown id="table-hidden-actions" placement="bottom-start">
                @foreach ($this->getHiddenActions() as $action)
                    <x-chief-table::action.dropdown.item :action="$action" />
                @endforeach
            </x-chief::dialog.dropdown>
        @endif

        @foreach ($this->getVisibleActions() as $action)
            <x-chief-table::action.button :action="$action" size="base" variant="primary" />
        @endforeach

        {{--
            // What if??? Source: Shopify
            // Add the secondary actions as buttons
            @foreach ($this->getSecondaryActions() as $action)
            <x-chief-table::action.button :action="$action" size="base" variant="secondary" />
            @endforeach
            // After the secondary actions and right before the primary actions, add the tertiary actions as dropdowns (now called 'hidden actions')
            @foreach ($this->getTertiaryActions() as $action)
            <x-chief-table::action.button :action="$action" size="base" variant="tertiary" />
            <x-chief::dialog.dropdown>
            ...
            </x-chief::dialog.dropdown>
            @endforeach
            // At the end of the row, add the primary actions
            @foreach ($this->getPrimaryActions() as $action)
            <x-chief-table::action.button :action="$action" size="base" variant="primary" />
            @endforeach
        --}}
    </div>
</div>
