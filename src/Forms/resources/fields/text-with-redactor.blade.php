<div
    class="w-full mb-1 external-editor-toolbar"
    id="js-external-editor-toolbar-{{ str_replace('.','_',$getElementId($locale ?? null)) }}"
></div>

<x-chief-form::formgroup.prepend-append
    :prepend="$getPrepend($locale ?? null)"
    :append="$getAppend($locale ?? null)"
>
    @include('chief-form::fields.html')
</x-chief-form::formgroup.prepend-append>
