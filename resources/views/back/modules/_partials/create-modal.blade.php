<?php
    $defaultLocale = config('app.fallback_locale');
    $page_id = isset($page_id) ? $page_id : null;
?>
<modal id="create-module" class="large-modal" title='' :active="{{ ($errors->has('morph_key') || $errors->has('slug')) ? 'true' : 'false' }}">
    <form v-cloak id="createModuleForm" method="POST" action="{{ route('chief.back.modules.store') }}" role="form">
        {{ csrf_field() }}

        <input type="hidden" name="page_id" value="{{ $page_id }}">
        <div class="stack-s">
            <label for="morphKeyField">Type</label>
            <chief-multiselect
                    id="morphKeyField"
                    name="module_key"
                    :options='@json(\Thinktomorrow\Chief\Modules\Module::available()->values()->toArray())'
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

    </form>

    <div slot="modal-action-buttons" v-cloak>
        <button type="button" class="btn btn-primary" data-submit-form="createModuleForm">Voeg module toe</button>
    </div>
</modal>
