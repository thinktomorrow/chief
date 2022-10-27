<x-slot name="labels">
    {!! $stateConfig->getStateLabel($model) !!}
</x-slot>

@switch($model->getState(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::KEY))
    @case(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::published)
        @php
            $isVisitable = ($model instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable);
            $isAnyLinkOnline = ($isVisitable && Thinktomorrow\Chief\Site\Urls\Form\LinkForm::fromModel($model)->isAnyLinkOnline());
        @endphp

        @if($isVisitable &&!$isAnyLinkOnline)
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
        @endif
        @break
    @case(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::draft)
        <div class="prose prose-spacing prose-dark">
            <p> Deze pagina is nog niet gepubliceerd en niet zichtbaar voor de gebruiker. </p>
        </div>
        @break
    @case(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::archived)
        <div class="prose prose-spacing prose-dark">
            <p> Deze pagina is gearchiveerd. </p>
        </div>
        @break
    @default
        <div class="prose prose-spacing prose-dark">
            <p> ... </p>
        </div>
@endswitch
