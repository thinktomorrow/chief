<?php
    $defaultLocale = config('app.locale');
?>
<modal id="create-module" class="large-modal" title='' :active="{{ $errors->has('trans.'.$defaultLocale.'.title') ? 'true' : 'false' }}">
    <form v-cloak id="createForm" method="POST" action="{{ route('chief.back.modules.store') }}" role="form">
        {{ csrf_field() }}

        <div class="stack-s">
            <label for="collectionField">Type</label>
            <chief-multiselect
                    id="collectionField"
                    name="collection"
                    :options='@json($collections)'
                    selected='@json(old('collection'))'
                    labelkey="label"
                    valuekey="id"
                    placeholder="..."
            >
            </chief-multiselect>

            <error class="caption text-warning" field="collection" :errors="errors.all()"></error>

        </div>

        <div class="stack-s">
            <label for="trans-{{ $defaultLocale }}-title">Titel</label>
            <input type="text" name="trans[{{ $defaultLocale }}][title]" id="trans-{{ $defaultLocale }}-title" class="input inset-s" placeholder="Titel" value="{{ old('trans.'.$defaultLocale.'.title') }}">
        </div>

        <error class="caption text-warning" field="trans.{{ $defaultLocale }}.title" :errors="errors.get('trans.{{ $defaultLocale }}')"></error>

    </form>

    <div slot="modal-action-buttons" v-cloak>
        <button type="submit" class="btn btn-primary" data-submit-form="createForm">Voeg module toe</button>
    </div>
</modal>
