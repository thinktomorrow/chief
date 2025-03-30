<div>

    <x-chief::window
            :title="$form->getTitle()"
            :badges="[]"
            :description="$form->getDescription()"
            :variant="$form->getFormDisplay()"
    >

        <x-slot name="actions">
            <x-chief::button wire:click="editForm" title="Aanpassen" size="sm" variant="grey">
                <x-chief::icon.quill-write />
            </x-chief::button>
        </x-slot>

        <div class="@container">
            <table class="w-full">
                <tbody
                        @class(['divide-y divide-grey-100', '@lg:[&>*:not(:first-child)_td]:pt-3 @lg:[&>*:not(:last-child)_td]:pb-3'])
                >
                @foreach ($this->getComponents() as $childComponent)
                    {{ $childComponent->renderPreview() }}
                @endforeach
                </tbody>
            </table>
        </div>

    </x-chief::window>

    <livewire:chief-wire::edit-form
            :key="'edit-form-'.$this->getId()"
            :model-reference="$modelReference"
            :form-component="$form"
            :parent-component-id="$this->getId()"
    />

</div>
