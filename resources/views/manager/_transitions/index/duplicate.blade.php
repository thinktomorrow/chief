<button type="submit" form="duplicateForm-{{ $model->getKey() }}">
    <x-chief::dialog.dropdown.item>Kopieer {{ lcfirst($resource->getLabel()) }}</x-chief::dialog.dropdown.item>
</button>

<form
    id="duplicateForm-{{ $model->getKey() }}"
    method="POST"
    action="@adminRoute('duplicate', $model)"
    class="hidden"
>
    @csrf
</form>
