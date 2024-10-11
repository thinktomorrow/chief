<div class="mt-2 flex items-start justify-between gap-4">
    <div class="flex items-start gap-2">
        @foreach ($this->getMainFilters() as $filter)
            <div data-filter-key="{{ $filter->getKey() }}">
                {!! $filter->render() !!}
            </div>
        @endforeach
    </div>

    <div class="ml-auto flex items-center justify-end gap-2">
        @foreach ($this->getVisibleActions() as $action)
            <x-chief-table::action.button :action="$action" size="base" variant="primary" />
        @endforeach

        @if (count($this->getHiddenActions()) > 0)
            <x-chief-table::button
                x-on:click="$dispatch('open-dialog', { 'id': 'table-hidden-actions' })"
                variant="tertiary"
            >
                <x-chief::icon.more-vertical-circle />
            </x-chief-table::button>

            <x-chief::dialog.dropdown id="table-hidden-actions" placement="bottom-end">
                @foreach ($this->getHiddenActions() as $action)
                    <x-chief-table::action.dropdown.item :action="$action" />
                @endforeach
            </x-chief::dialog.dropdown>
        @endif
    </div>
</div>
