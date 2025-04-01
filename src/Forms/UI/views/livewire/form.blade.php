<x-chief::window :title="$form->getTitle()" :variant="$form->getFormDisplay()">
    <x-slot name="actions">
        <x-chief::button wire:click="editForm" title="Aanpassen" size="sm" variant="grey">
            <x-chief::icon.quill-write />
        </x-chief::button>
    </x-slot>

    @foreach ($this->getComponents() as $childComponent)
        {{ $childComponent->renderPreview() }}
    @endforeach

    <livewire:chief-wire::edit-form
        :key="'edit-form-'.$this->getId()"
        :model-reference="$modelReference"
        :form-component="$form"
        :parent-component-id="$this->getId()"
    />
</x-chief::window>
