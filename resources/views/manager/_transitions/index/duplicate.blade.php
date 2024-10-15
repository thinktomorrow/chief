<div>
    <x-chief::dialog.dropdown.item form="duplicateForm-{{ $model->getKey() }}" type="submit">
        <x-chief::icon.copy />
        <x-chief::dialog.dropdown.item.content label="Kopiëer {{ lcfirst($resource->getLabel()) }}">
            <p>Hiermee maak je een kopie van deze pagina aan, die je meteen kan bewerken.</p>
        </x-chief::dialog.dropdown.item.content>
    </x-chief::dialog.dropdown.item>

    <form
        id="duplicateForm-{{ $model->getKey() }}"
        method="POST"
        action="@adminRoute('duplicate', $model)"
        class="hidden"
    >
        @csrf
    </form>
</div>
