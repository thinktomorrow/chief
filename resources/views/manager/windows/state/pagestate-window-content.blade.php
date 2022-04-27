

@switch($model->getPageState())
    @case(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::published)

        @php
            $isVisitable = ($model instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable);
            $isAnyLinkOnline = ($isVisitable && Thinktomorrow\Chief\Site\Urls\Form\LinkForm::fromModel($model)->isAnyLinkOnline());
        @endphp

        @if($isAnyLinkOnline)
            <x-slot name="labels">
                <span title="Deze pagina is gepubliceerd en zichtbaar voor de gebruiker!" class="label label-success">Online</span>
            </x-slot>
        @elseif($isVisitable)
            <x-slot name="labels">
                <span class="label label-warning">Nog niet online</span>
            </x-slot>

            <div class="prose prose-spacing prose-dark">
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
    @case(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::draft)
    <x-slot name="labels">
        <span class="label label-warning">Draft</span>
    </x-slot>

    <div class="prose prose-spacing prose-dark">
        <p> Deze pagina is nog niet gepubliceerd en niet zichtbaar voor de gebruiker. </p>
    </div>

    @break
    @case(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::archived)
    <x-slot name="labels">
        <span class="label label-warning">Gearchiveerd</span>
    </x-slot>

    <div class="prose prose-spacing prose-dark">
        <p> Deze pagina is gearchiveerd. </p>
    </div>

    @break
    @default
    <div class="prose prose-spacing prose-dark">
        <p> ... </p>
    </div>
@endswitch
