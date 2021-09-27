<div class="w-full external-editor-toolbar" id="js-external-editor-toolbar-{{ str_replace('.','_',$field->getId($locale ?? null)) }}"></div>

<div class="flex">
    @if($field->getPrepend())
        <div class="prepend-to-input">
            {!! $field->getPrepend($locale ?? null) !!}
        </div>
    @endif

    @if($field->allowsHtmlOptions())
        <div class="w-full {{ $field->getPrepend() ? 'with-prepend' : null }} {{ $field->getAppend() ? 'with-append' : null }}">
            @include('chief::editors.' . config('chief.editor', 'redactor') . '.input')
        </div>
    @else
        <input
            type="text"
            name="{{ $field->getName($locale ?? null) }}"
            id="{{ $field->getId($locale ?? null) }}"
            placeholder="{{ $field->getPlaceholder($locale ?? null) }}"
            value="{{ old($field->getDottedName($locale ?? null), $field->getValue($locale ?? null)) }}"
            class="{{ $field->getPrepend() ? 'with-prepend' : null }} {{ $field->getAppend() ? 'with-append' : null }}"
            {{ (isset($autofocus) && $autofocus) ? 'autofocus' : '' }}
        >
    @endif

    @if($field->getAppend())
        <div class="append-to-input">
            {!! $field->getAppend($locale ?? null) !!}
        </div>
    @endif
</div>

@if($field->hasCharacterCount())
    @include('chief::manager.fields.form.types.charactercount')
@endif
