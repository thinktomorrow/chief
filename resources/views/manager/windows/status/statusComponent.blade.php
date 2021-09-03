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
                        <span class="space-x-1">
                            <span>Status</span>
                            <span class="text-xs label label-success">Online</span>
                        </span>
                    </x-slot>

                    <div class="prose prose-dark">
                        <p>
                            Deze pagina is gepubliceerd en zichtbaar voor de gebruiker!
                        </p>
                    </div>

                @elseif($isVisitable)
                    <x-slot name="title">
                        <span class="inline-flex items-center space-x-1">
                            <span>Status</span>
                            <span class="text-xs label label-info">Nog niet online</span>
                        </span>
                    </x-slot>

                    <div class="prose prose-dark">
                        <p>
                            Let op! De pagina is zonder link nog niet zichtbaar op de site.
                        </p>

                        <p>
                            <a
                                class="link link-primary"
                                data-sidebar-trigger="links"
                                href="{{ $manager->route('links-edit', $model) }}"
                            >
                                <x-chief-icon-label type="add">Voeg een eerste link toe</x-chief-icon-label>
                            </a>
                        </p>
                    </div>
                @else
                    <x-slot name="title">
                            <span class="inline-flex items-center space-x-1">
                                <span>Status</span>
                                <span class="text-xs label label-success">Online</span>
                            </span>
                    </x-slot>
                @endif

                @break
            @case('draft')
                <x-slot name="title">
                    <span class="inline-flex items-center space-x-1">
                        <span>Status</span>
                        <span class="text-xs label label-warning">Draft</span>
                    </span>
                </x-slot>

                <p class="text-grey-700">
                    Deze pagina is nog niet gepubliceerd en niet zichtbaar voor de gebruiker.
                </p>
                @break
            @case('archived')
                <x-slot name="title">
                    <span class="inline-flex items-center space-x-1">
                        <span>Status</span>
                        <span class="text-xs label label-warning">Gearchiveerd</span>
                    </span>
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
