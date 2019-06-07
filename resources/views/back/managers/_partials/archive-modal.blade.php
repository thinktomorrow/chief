<?php $managedModelId = \Illuminate\Support\Str::slug( $manager->assistant('archive')->route('archive')); ?>

<modal id="archive-manager-{{ $managedModelId }}" class="large-modal" title=''>
    <form action="{{ $manager->assistant('archive')->route('archive') }}" method="POST" id="archive-manager-form-{{ $managedModelId }}">
        @csrf
        <div v-cloak>
            <h2 class="formgroup-label">Archiveer de pagina {{ $manager->details()->title }}</h2>
            <p>Dit haalt de pagina van de site. Geef hier aan naar welke pagina de links mogen doorlinken.</p>
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
            </div>
        </div>
    </form>

    <div v-cloak slot="modal-action-buttons">
        <button type="button" class="btn btn-o-tertiary stack" data-submit-form="archive-manager-form-{{ $managedModelId }}">Archiveer</button>
    </div>
</modal>
