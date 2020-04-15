<?php
    $defaultLocale = config('app.fallback_locale');
    $owner_type = isset($owner_type) ? $owner_type : null;
    $owner_id = isset($owner_id) ? $owner_id : null;
?>
<modal id="create-module" class="large-modal" title='' :active="{{ ($errors->has('morph_key') || $errors->has('slug')) ? 'true' : 'false' }}">
    <form v-cloak id="createModuleForm" method="POST" action="{{ route('chief.back.modules.store') }}" role="form">
        {{ csrf_field() }}

        <input type="hidden" name="owner_type" value="{{ $owner_type }}">
        <input type="hidden" name="owner_id" value="{{ $owner_id }}">
        <div class="stack-s">
            <label for="morphKeyField">Type</label>
            <chief-multiselect
                    id="morphKeyField"
                    name="module_key"
                    :options='@json(\Thinktomorrow\Chief\Modules\Module::availableForCreation()->values()->toArray())'
                    selected='@json(old('module_key'))'
                    labelkey="singular"
                    valuekey="key"
                    placeholder="..."
            >
            </chief-multiselect>

            <error class="caption text-warning" field="module_key" :errors="errors.all()"></error>

        </div>

        <div class="stack-s">
            <label for="slugField">Interne titel</label>
            <input type="text" name="slug" id="slugField" class="input inset-s" placeholder="e.g. nieuwsbrief, contacteer-ons, homepage-banner" value="{{ old('slug') }}">
        </div>

        <error class="caption text-warning" field="slug" :errors="errors.all()"></error>

        <div class="stack-s">
            <p class="text-warning">Opgelet. <br>Bewaar eerst deze pagina indien je niet bewaarde aanpassingen hebt.<br>Anders zullen deze verloren gaan.</p>
        </div>

    </form>

    <div slot="modal-action-buttons" v-cloak>
        <button type="button" class="btn btn-primary" data-submit-form="createModuleForm">Voeg module toe</button>
    </div>
</modal>
