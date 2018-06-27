<?php
    $defaultLocale = config('app.locale');
?>
<modal id="create-module" class="large-modal" title='' :active="{{ $errors->any() ? 'true' : 'false' }}">
    <form v-cloak id="createForm" method="POST" action="{{ route('chief.back.modules.store') }}" role="form">
        {{ csrf_field() }}

        <div class="stack-s">
            <label for="collectionField">Type</label>
            <chief-multiselect
                    id="collectionField"
                    name="collection"
                    :options='@json($collections)'
                    selected='@json(old('collection'))'
                    labelkey="singular"
                    valuekey="key"
                    placeholder="..."
            >
            </chief-multiselect>

            <error class="caption text-warning" field="collection" :errors="errors.all()"></error>

        </div>

        <div class="stack-s">
            <label for="slugField">Interne titel</label>
            <input type="text" name="slug" id="slugField" class="input inset-s" placeholder="e.g. nieuwsbrief, contacteer-ons, homepage-banner" value="{{ old('slug') }}">
        </div>

        <error class="caption text-warning" field="slug" :errors="errors.all()"></error>

    </form>

    <div slot="modal-action-buttons" v-cloak>
        <button type="submit" class="btn btn-primary" data-submit-form="createForm">Voeg module toe</button>
    </div>
</modal>
