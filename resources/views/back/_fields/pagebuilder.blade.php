<section class="formgroup stack-l">
    <page-builder
            :locales='@json($field->locales)'
            :default-sections='@json($field->sections)'
            :modules='@json($field->availableModules)'
            :pages='@json($field->availablePages)'
            :pagesets='@json($field->availableSets)'
            :text-editor='@json(config('thinktomorrow.chief.editor'))'>
    </page-builder>
</section>
