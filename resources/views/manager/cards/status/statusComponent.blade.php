<div data-status-component>
    <x-chief-card
        class="{{ isset($class) ? $class : '' }}"
        :editRequestUrl="$manager->route('status-edit', $model)"
        type="status"
    >
        @switch($model->getPageState())
            @case('published')
                @if($isAnyLinkOnline)
                    <x-slot name="title">
                        Status
                        <span class="text-sm label label-success">Online</span>
                    </x-slot>

                    <p class="text-grey-700">
                        Deze pagina is gepubliceerd en zichtbaar voor de gebruiker!
                    </p>
                @else
                    <x-slot name="title">
                        Status
                        <span class="text-sm label label-info">Gepubliceerd</span>
                    </x-slot>

                    <p class="text-grey-700">
                        Let op, deze pagina is nog niet zichtbaar voor de gebruiker.
                        Daarvoor moet je eerst een url toevoegen aan de pagina.
                    </p>
                @endif

                @break
            @case('draft')
                <x-slot name="title">
                    Status
                    <span class="text-sm label label-warning">Draft</span>
                </x-slot>

                <p class="text-grey-700">
                    Deze pagina is nog niet gepubliceerd en niet zichtbaar voor de gebruiker.
                </p>
                @break
            @case('archived')
                <x-slot name="title">
                    Status
                    <span class="text-sm label label-warning">Gearchiveerd</span>
                </x-slot>

                <p class="text-grey-700">
                    Deze pagina is gearchiveerd.
                </p>
                @break
            @default
                <x-slot name="title"></x-slot>
                    Status
                </x-slot>

                <p class="text-grey-700">...</p>
        @endswitch
    </x-chief-card>
</div>
