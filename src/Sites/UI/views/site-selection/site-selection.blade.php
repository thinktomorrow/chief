<div>
    <div class="flex items-center gap-2">
        @if($this->isAllowedToSelectSites() || count($this->getSites()) > 1)
            <livewire:chief-wire::model-site-toggle :model="$this->model" />
        @endif

        @if($this->isAllowedToSelectSites())
            <x-chief::button wire:click="edit" size="sm" variant="grey" title="Sites aanpassen" class="shrink-0">
                <x-chief::icon.settings />
            </x-chief::button>

            <livewire:chief-wire::edit-site-selection key="edit-sites" :model="$this->model" />
        @endif
    </div>

    {{--
        <x-chief::window title="Sites" class="hidden">
        <x-slot name="actions">
        <x-chief::button wire:click="edit" size="sm" variant="grey" title="Sites aanpassen" class="shrink-0">
        <x-chief::icon.quill-write />
        </x-chief::button>
        </x-slot>

        @if (count($sites) > 0)
        <div class="space-y-1">
        @foreach ($sites as $site)
        <x-chief::badge wire:key="site-{{ $site->locale }}" size="sm">
        {{ $site->name }}
        </x-chief::badge>
        @endforeach
        </div>
        @else
        <p class="body text-grey-500">Nog geen sites geselecteerd.</p>
        @endif

        </x-chief::window>
    --}}
</div>
