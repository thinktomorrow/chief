<div class="mt-2 flex items-start justify-between gap-4">
    <div>
        <div class="flex items-start gap-2">
            @foreach ($this->getMainFilters() as $filter)
                <div data-filter-key="{{ $filter->getKey() }}">
                    {!! $filter->render() !!}
                </div>
            @endforeach
        </div>
    </div>
    <div class="ml-auto flex items-center justify-end gap-2">
        @foreach ($this->getVisibleActions() as $action)
            {{ $action }}
        @endforeach

        @if (count($this->getHiddenActions()) > 0)
            <button type="button" x-on:click="$dispatch('open-dialog', { 'id': 'table-hidden-actions' })">
                <x-chief-table::button color="white">
                    <x-chief::icon.more-vertical-circle />
                </x-chief-table::button>
            </button>

            <x-chief::dialog.dropdown id="table-hidden-actions" placement="bottom-end">
                @foreach ($this->getHiddenActions() as $action)
                    {{ $action }}
                @endforeach
            </x-chief::dialog.dropdown>
        @endif
    </div>
</div>
