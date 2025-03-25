<x-slot name="labels">
    {!! $stateConfig->getStateLabel($model) !!}
</x-slot>

@switch($model->getState(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::KEY))
    @case(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::published)
        @php
            $isVisitable = $model instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable;
            $isAnyLinkOnline = $isVisitable && Thinktomorrow\Chief\Site\Urls\Form\LinkForm::fromModel($model)->isAnyLinkOnline();
        @endphp

        @if ($isVisitable && ! $isAnyLinkOnline)
            <div class="prose prose-dark prose-spacing">
                <p>Let op! De pagina is zonder link nog niet zichtbaar op de site.</p>
                <p>
                    <x-chief::link
                        data-sidebar-trigger="links"
                        href="{{ $manager->route('links-edit', $model) }}"
                        title="Aanpassen"
                    >
                        <x-chief::icon.plus-sign />
                        <span>Voeg een eerste link toe</span>
                    </x-chief::link>
                </p>
            </div>
        @endif

        @break
    @case(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::draft)
        <div class="prose prose-dark prose-spacing">
            <p>Offline en niet zichtbaar voor de bezoeker.</p>
        </div>

        @break
    @case(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::archived)
        <div class="prose prose-dark prose-spacing">
            <p>Gearchiveerd.</p>
        </div>

        @break
    @default
        <div class="prose prose-dark prose-spacing">
            <p>...</p>
        </div>
@endswitch
