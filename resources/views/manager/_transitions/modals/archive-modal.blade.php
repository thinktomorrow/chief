<modal id="archive-manager-{{ $model->id }}" title="Ben je zeker?">
    <form
        action="@adminRoute('archive', $model)"
        method="POST"
        id="archive-manager-form-{{ $model->id }}"
        v-cloak
    >
        @csrf

        <h2>Archiveer: @adminConfig('pageTitle')</h2>

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
            <p>Archiveren haalt de @adminConfig('pageTitle') onmiddellijk van de site.</p>
        @endif
    </form>

    <div v-cloak slot="modal-action-buttons">
        <button type="button" class="btn btn-warning" data-submit-form="archive-manager-form-{{ $model->id }}">Archiveren</button>
    </div>
</modal>
