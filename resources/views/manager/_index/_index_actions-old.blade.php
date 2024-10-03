@adminCan('create')
<a href="@adminRoute('create')" title="Nieuwe {{ lcfirst($resource->getLabel()) }} maken" class="btn btn-primary">
    <x-chief::icon-label type="add">Voeg {{ lcfirst($resource->getLabel()) }} toe</x-chief::icon-label>
</a>
@endAdminCan

@if ($manager->can('duplicate') && ((isset($models) && $models->isNotEmpty()) || (isset($tree) && ! $tree->isEmpty())))
    <button type="button" x-on:click="$dispatch('open-dialog', { 'id': 'index-options' })">
        <x-chief::button>
            <x-chief::icon.more-vertical-circle class="size-5" />
        </x-chief::button>
    </button>

    <x-chief::dialog.dropdown id="index-options">
        @adminCan('duplicate')
        <button
            x-data="{}"
            type="button"
            x-on:click="$dispatch('open-dialog', { id: 'duplicate-modal-on-create' })"
        >
            <x-chief::dialog.dropdown.item>Kopieer een bestaande ...</x-chief::dialog.dropdown.item>

            <template x-teleport="body">
                <x-chief::dialog.modal
                    id="duplicate-modal-on-create"
                    title="Kies een {{ lcfirst($resource->getLabel()) }} om te kopiëren"
                    size="xs"
                >
                    <form
                        id="duplicateFormOnCreate"
                        method="POST"
                        action="@adminRoute('duplicate-on-create')"
                        class=""
                    >
                        @csrf

                        {{-- Multiselect choices panel is hidden in modal because of overflow hidden so that's why we use the native select here. --}}
                        {{-- <x-chief::multiselect name="model_id" :options="$originalModels" /> --}}
                        <x-chief::input.select id="originalModelId" name="model_id" class="my-4" required>
                            <option value="">---</option>
                            @foreach ($originalModels as $originalModel)
                                <option value="{{ $originalModel['value'] }}">{{ $originalModel['label'] }}</option>
                            @endforeach
                        </x-chief::input.select>
                    </form>

                    <x-slot name="footer">
                        <button type="submit" x-on:click="open = false" class="btn btn-grey">Annuleer</button>

                        <button type="submit" form="duplicateFormOnCreate" class="btn btn-primary">Kopieer</button>
                    </x-slot>
                </x-chief::dialog.modal>
            </template>
        </button>
        @endAdminCan
    </x-chief::dialog.dropdown>
@endif
