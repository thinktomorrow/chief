@php
    $sites = $this->getSites();
@endphp

<x-chief::window title="Sitekeuze">
    <x-slot name="actions">
        @if ($model instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract && chiefAdmin()->can('update-page'))
            @foreach ($model->getStateKeys() as $stateKey)
                <livewire:chief-wire::state :model="$model" :state-key="$stateKey" />
            @endforeach
        @endif
        
        <x-chief::button wire:click="edit" size="sm" variant="grey" title="Sites aanpassen" class="shrink-0">
            <x-chief::icon.quill-write />
        </x-chief::button>
    </x-slot>

    @if (count($sites) > 0)
        <div class="space-y-1">
            @foreach ($sites as $site)
                <x-chief::badge wire:key="site-{{ $site->locale }}">
                    {{ $site->name }}
                </x-chief::badge>
            @endforeach
        </div>
    @else
        <p class="body text-grey-500">Nog geen sites geselecteerd.</p>
    @endif

    <livewire:chief-wire::edit-site-selection key="edit-sites" :model-reference="$modelReference" />
</x-chief::window>
