<div>
    <div class="flex items-start justify-stretch gap-3">
        @foreach ($this->getComponents() as $childComponent)
            {{ $childComponent->label('')->renderPreview() }}
        @endforeach

        <x-chief::button
            wire:click="editForm"
            title="Aanpassen"
            size="sm"
            variant="grey"
            class="mt-[0.4375rem] shrink-0"
        >
            <x-chief::icon.quill-write />
        </x-chief::button>
    </div>

    <livewire:chief-wire::edit-form
        :key="'edit-form-'.$this->getId()"
        :model-reference="$modelReference"
        :form-component="$form"
        :parent-component-id="$this->getId()"
    />
</div>
