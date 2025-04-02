<x-chief::window
    :title="$form->getTitle()"
    :variant="$form->getFormDisplay()"
    {{-- TODO(tijs): you can do cleaner than this --}}
    x-data="{
        formUpdated: false,
        init() {
            $wire.on('form-updated-{{ $this->getId() }}', () => {
                this.formUpdated = true;
                setTimeout(() => {
                    this.formUpdated = false;
                }, 2500);
            });
        }
    }"
>
    <x-slot name="actions">
        <x-chief::badge
            x-cloak
            x-show="formUpdated"
            variant="green"
            class="animate-pop-in-badge mt-[0.3125rem] inline-flex items-center gap-0.5"
        >
            <span>Opgeslagen</span>
        </x-chief::badge>

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
