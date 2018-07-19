<?php
    $defaultLocale = config('app.locale');
?>
<modal id="create-page" class="large-modal" title='' :active="{{ $errors->has('trans.'.$defaultLocale.'.title') ? 'true' : 'false' }}">
    <form v-cloak id="createForm" method="POST" action="{{ route('chief.back.pages.store', $page->collectionKey()) }}" role="form">
        {{ csrf_field() }}

        <div class="stack-s">
            <label for="trans-{{ $defaultLocale }}-title">Titel</label>
            <input type="text" name="trans[{{ $defaultLocale }}][title]" id="trans-{{ $defaultLocale }}-title" class="input inset-s" placeholder="Titel" value="{{ old('trans.'.$defaultLocale.'.title', $page->translateForForm($defaultLocale,'title')) }}">
        </div>

        <error class="caption text-warning" field="trans.{{ $defaultLocale }}.title" :errors="errors.get('trans.{{ $defaultLocale }}')"></error>

    </form>

    <div slot="modal-action-buttons" v-cloak>
        <button type="button" class="btn btn-primary" data-submit-form="createForm">Voeg toe</button>
    </div>
</modal>
