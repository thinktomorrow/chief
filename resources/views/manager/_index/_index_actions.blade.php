<button type="button" id="index-options" class="btn btn-primary">
    <x-chief::icon-label type="add">Voeg {{ lcfirst($resource->getLabel()) }} toe</x-chief::icon-label>
</button>

<x-chief::dropdown trigger="#index-options">

    @adminCan('create')
    <a href="@adminRoute('create')" title="Nieuwe {{ lcfirst($resource->getLabel()) }} maken" class="">
        <x-chief::dropdown.item>
            Creëer een {{ lcfirst($resource->getLabel()) }}
        </x-chief::dropdown.item>
    </a>
    @endAdminCan

    @adminCan('duplicate')
    <button x-data="{}" type="button" x-on:click="$dispatch('open-dialog', { id: 'duplicate-modal-on-create'})">
        <x-chief::dropdown.item>
            Kopieer een bestaande ...
        </x-chief::dropdown.item>

        <template x-teleport="body">
            <x-chief::dialog id="duplicate-modal-on-create" title="Kies een {{ lcfirst($resource->getLabel()) }} om te kopiëren" size="xs">
                <form id="duplicateFormOnCreate" method="POST" action="@adminRoute('duplicate-on-create')" class="">
                    @csrf

                    {{-- Multiselect choices panel is hidden in modal because of overflow hidden so that's why we use the native select here.                                 --}}
                    {{-- <x-chief::multiselect name="model_id" :options="$originalModels" />--}}
                    <x-chief::input.select id="originalModelId" name="model_id" class="my-4" required>
                        <option value="">---</option>
                        @foreach($originalModels as $originalModel)
                            <option value="{{ $originalModel['value'] }}">{{ $originalModel['label'] }}</option>
                        @endforeach
                    </x-chief::input.select>


                </form>

                <x-slot name="footer">
                    <button type="submit" x-on:click="open = false" class="btn btn-grey">
                        Annuleer
                    </button>

                    <button
                            type="submit"
                            form="duplicateFormOnCreate"
                            class="btn btn-primary">Kopieer</button>
                </x-slot>
            </x-chief::dialog>

        </template>

    </button>
    @endAdminCan
</x-chief::dropdown>
