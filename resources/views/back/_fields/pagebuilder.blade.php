<section class="formgroup stack-l">
    <page-builder
            :locales='@json($field->getLocales())'
            :default-sections='@json($field->getSections())'
            :modules='@json($field->getAvailableModules())'
            :pages='@json($field->getAvailablePages())'
            :pagesets='@json($field->getAvailableSets())'
            :text-editor='@json(config('thinktomorrow.chief.editor'))'>
    </page-builder>
</section>
