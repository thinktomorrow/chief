<modal id="archive-manager-{{ $model->id }}" title="Ben je zeker?">
    <form
        action="@adminRoute('archive', $model)"
        method="POST"
        id="archive-manager-form-{{ $model->id }}"
        v-cloak
    >
        @csrf

        <h2 class="h2 display-dark">Archiveer: {{ $resource->getPageTitle($model) }}</h2>

        @if(contract($model, \Thinktomorrow\Chief\Site\Visitable\Visitable::class))
            <p>
                Opgelet, dit haalt deze pagina van de site en bezoekers krijgen een 404-pagina te zien.
                Je kan ook kiezen om door te linken naar een andere pagina.
            </p>

            <p>Stel die pagina hieronder in:</p>

            <chief-multiselect
                name="redirect_id"
                :options='@json($targetModels)'
                selected='@json(old('redirect_id'))'
                grouplabel="group"
                groupvalues="values"
                labelkey="label"
                valuekey="id"
                class="mt-3"
            ></chief-multiselect>
        @else
            <p>Archiveren haalt de {{ $resource->getPageTitle($model) }} onmiddellijk van de site.</p>
        @endif
    </form>

    <div v-cloak slot="modal-action-buttons">
        <button type="submit" class="btn btn-warning" form="archive-manager-form-{{ $model->id }}">Archiveren</button>
    </div>
</modal>
