<div>
    <div class="flex items-center gap-2">
        @if ($this->modelAllowsLocaleSelection() || count($this->getSites()) > 1)
            <livewire:chief-wire::model-site-toggle :model="$this->model" />
        @endif

        @if ($this->modelAllowsLocaleSelection())
            <x-chief::button wire:click="edit" size="sm" variant="grey" title="Sites aanpassen" class="shrink-0">
                <x-chief::icon.settings />
            </x-chief::button>

            <template x-teleport="body">
                <livewire:chief-wire::edit-site-selection key="edit-sites" :model="$this->model" />
            </template>
        @endif
    </div>
</div>
