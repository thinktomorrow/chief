@push('portals')
    <?php $managedModelId = $model->id; ?>

    <modal id="archive-manager-{{ $managedModelId }}" class="large-modal" title=''>
        <form action="@adminRoute('archive', $model)" method="POST" id="archive-manager-form-{{ $managedModelId }}">
            @csrf
            <div v-cloak>
                <h2 class="formgroup-label">Archiveer: @adminConfig('pageTitle')</h2>
                @if(contract($model, \Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl::class))
                    <p>
                        Opgelet, dit haalt deze pagina van de site en bezoekers krijgen een 404-pagina te zien.<br>
                        Je kan ook kiezen om door te linken naar een andere pagina. Stel die pagina hieronder in.
                    </p>
                    <div class="input-group stack column-6">
                        <chief-multiselect
                                name="redirect_id"
                                :options='@json($targetModels)'
                                selected='@json(old('redirect_id'))'
                                grouplabel="group"
                                groupvalues="values"
                                labelkey="label"
                                valuekey="id"
                        >
                        </chief-multiselect>
                    @else
                        <p>
                            Archiveren haalt de @adminConfig('pageTitle') onmiddellijk van de site.<br>
                        </p>
                    @endif
                </div>
            </div>
        </form>

        <div v-cloak slot="modal-action-buttons">
            <button type="button" class="btn btn-primary" data-submit-form="archive-manager-form-{{ $managedModelId }}">Archiveer</button>
        </div>
    </modal>
@endpush
