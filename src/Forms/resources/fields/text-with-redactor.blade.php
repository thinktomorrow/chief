<div
    class="external-editor-toolbar mb-1 w-full"
    id="js-external-editor-toolbar-{{ str_replace('.', '_', $getElementId($locale ?? null)) }}"
></div>

<x-chief::form.input.prepend-append :prepend="$getPrepend($locale ?? null)" :append="$getAppend($locale ?? null)">
    @include('chief-form::fields.html')
</x-chief::form.input.prepend-append>
