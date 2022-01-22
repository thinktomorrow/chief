<div class="w-full external-editor-toolbar" id="js-external-editor-toolbar-{{ str_replace('.','_',$getElementId($locale ?? null)) }}"></div>

<x-chief-forms::formgroup.prepend-append
    :prepend="$getPrepend($locale ?? null)"
    :append="$getAppend($locale ?? null)"
>
    @include('chief-forms::fields.html')
</x-chief-forms::formgroup.prepend-append>
