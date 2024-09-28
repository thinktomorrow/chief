<div class="flex items-start justify-between gap-4">
    <div class="ml-auto flex items-center justify-end gap-2">
        @foreach ($this->getVisibleActions() as $action)
            {{ $action }}
        @endforeach

        @if (count($this->getHiddenActions()) > 0)
            <button type="button" x-on:click="$dispatch('open-dialog', { 'id': 'table-hidden-actions' })">
                <x-chief-table::button
                    color="white"
                    iconRight='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none"> <path d="M13.5 4.5C13.5 3.67157 12.8284 3 12 3C11.1716 3 10.5 3.67157 10.5 4.5C10.5 5.32843 11.1716 6 12 6C12.8284 6 13.5 5.32843 13.5 4.5Z" stroke="currentColor" stroke-width="1.5" /> <path d="M13.5 12C13.5 11.1716 12.8284 10.5 12 10.5C11.1716 10.5 10.5 11.1716 10.5 12C10.5 12.8284 11.1716 13.5 12 13.5C12.8284 13.5 13.5 12.8284 13.5 12Z" stroke="currentColor" stroke-width="1.5" /> <path d="M13.5 19.5C13.5 18.6716 12.8284 18 12 18C11.1716 18 10.5 18.6716 10.5 19.5C10.5 20.3284 11.1716 21 12 21C12.8284 21 13.5 20.3284 13.5 19.5Z" stroke="currentColor" stroke-width="1.5" /> </svg>'
                />
            </button>

            <x-chief::dialog.dropdown id="table-hidden-actions" placement="bottom-end">
                @foreach ($this->getHiddenActions() as $action)
                    {{ $action }}
                @endforeach
            </x-chief::dialog.dropdown>
        @endif
    </div>
</div>
