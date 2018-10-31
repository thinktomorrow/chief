<section class="formgroup stack">
    @if($field->label)
        <h2 class="formgroup-label"><label for="{{ $key }}">{{ $field->label }}</label></h2>
    @endif
    <page-builder
            :locales='@json($field->locales)'
            :default-sections='@json($field->sections)'
            :modules='@json($field->availableModules)'
            :pages='@json($field->availablePages)'
            :pagesets='@json($field->availableSets)'>
    </page-builder>
</section>
