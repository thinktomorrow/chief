<button type="submit" form="duplicateForm-{{ $model->getKey() }}">
    <x-chief::dropdown.item>
        Kopieer {{ lcfirst($resource->getLabel()) }}
    </x-chief::dropdown.item>
</button>

<form id="duplicateForm-{{ $model->getKey() }}" method="POST" action="@adminRoute('duplicate', $model)" class="hidden">
    @csrf
</form>
