<section class="formgroup stack">
    <page-builder
            :locales='@json($field->locales)'
            :default-sections='@json($field->sections)'
            :modules='@json($field->availableModules)'
            :pages='@json($field->availablePages)'
            :pagesets='@json($field->availableSets)'>
    </page-builder>
</section>
