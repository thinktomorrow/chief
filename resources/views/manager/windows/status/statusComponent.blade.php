<div data-status-component>
    <x-chief::window title="Status" :url="$manager->route('status-edit', $model)" sidebar="status">
        @switch($model->getPageState())
            @case('published')
                @if($isAnyLinkOnline)
                    <x-slot name="labels">
                        <span class="label label-success">Online</span>
                    </x-slot>

                    <div class="prose prose-dark">
                        <p> Deze pagina is gepubliceerd en zichtbaar voor de gebruiker! </p>
                    </div>
                @elseif($isVisitable)
                    <x-slot name="labels">
                        <span class="label label-info">Nog niet online</span>
                    </x-slot>

                    <div class="prose prose-dark">
                        <p> Let op! De pagina is zonder link nog niet zichtbaar op de site. </p>

                        <p>
                            <a
                                data-sidebar-trigger="links"
                                href="{{ $manager->route('links-edit', $model) }}"
                                title="Aanpassen"
                                class="link link-primary"
                            >
                                <x-chief-icon-label type="add"> Voeg een eerste link toe </x-chief-icon-label>
                            </a>
                        </p>
                    </div>
                @else
                    <x-slot name="labels">
                        <span class="label label-success">Online</span>
                    </x-slot>
                @endif

                @break
            @case('draft')
                <x-slot name="labels">
                    <span class="label label-warning">Draft</span>
                </x-slot>

                <div class="prose prose-dark">
                    <p> Deze pagina is nog niet gepubliceerd en niet zichtbaar voor de gebruiker. </p>
                </div>

                @break
            @case('archived')
                <x-slot name="labels">
                    <span class="label label-warning">Gearchiveerd</span>
                </x-slot>

                <div class="prose prose-dark">
                    <p> Deze pagina is gearchiveerd. </p>
                </div>

                @break
            @default
                <div class="prose prose-dark">
                    <p> ... </p>
                </div>
        @endswitch
    </x-chief-card>
</div>
