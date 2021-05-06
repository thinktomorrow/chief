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
                        <span class="text-sm label label-info">Nog niet gepubliceerd</span>
                    </x-slot>

                    <p class="text-grey-700">
                        Let op! De pagina is zonder link nog niet zichtbaar op de site.
                    </p>
                    <p class="text-grey-700 mt-4">
                        <a class="block link link-primary" data-sidebar-trigger="links" href="{{ $manager->route('links-edit', $model) }}">Voeg een eerste link toe</a>
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
