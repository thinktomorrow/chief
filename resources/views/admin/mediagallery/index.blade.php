<x-chief::page.template title="Media">
    <x-chief::page.grid>
        <form method="POST" action="{{ route('chief.mediagallery.bulk') }}" id="selecting" class="card">
            <div class="flex items-center justify-between mb-4">
                <label for="select-all" class="with-checkbox">
                    <input type="checkbox" name="select_all" id="select-all">
                    <span>Alles selecteren</span>
                </label>

                <div class="space-x-2">
                    <button type="submit" form="selecting" name="type" value="download" class="btn btn-primary">Download de selectie</button>
                    <button v-cloak @click="showModal('mediagallery-bulk-delete-modal')" type="button" class="btn btn-error-outline">Verwijder de selectie</button>
                </div>
            </div>

            <div class="row-start-start gutter-3">
                @foreach($assets as $index => $asset)
                    <div class="w-1/2 xl:w-1/3 2xl:w-1/4">
                        @include('chief::admin.mediagallery.item')
                    </div>
                @endforeach
            </div>
        </form>

        {{ $assets->links('chief::pagination.default') }}

        <x-slot name="aside">
            <x-chief-window title="Filter" class="card">
                <form method="GET" id="filtering" class="space-y-4">
                    <span class="text-grey-500">{{ $assets->total() }} resultaten</span>

                    <x-chief-form::formgroup id="name" label="Bestandsnaam">
                        <input
                            type="text"
                            name="search"
                            placeholder="Zoek op bestandsnaam ..."
                            value="{{ old('search', request()->input('search'))}}"
                        >
                    </x-chief-form::formgroup>

                    <x-chief-form::formgroup id="owner" label="Pagina">
                        <chief-multiselect
                            name="owner"
                            :options='@json($pages)'
                            selected='@json(old('owner', request()->input('owner')))'
                            :multiple='false'
                            grouplabel="group"
                            groupvalues="values"
                            labelkey="label"
                            valuekey="id"
                        ></chief-multiselect>
                    </x-chief-form::formgroup>

                    <x-chief-form::formgroup id="unused">
                        <label for="unused" class="with-checkbox">
                            <input
                                type="checkbox"
                                name="unused"
                                id="unused"
                                {{ old('unused', request()->input('unused')) ? 'checked' : '' }}
                            >

                            <span>Toon enkel ongebruikte media</span>
                        </label>
                    </x-chief-form::formgroup>

                    <button type="submit" form="filtering" class="btn btn-primary">Filter</button>
                </form>
            </x-chief-window>
        </x-slot>
    </x-chief::page.grid>

    <modal id="mediagallery-bulk-delete-modal" title="Selectie verwijderen">
        <h2 class="h2 h1-dark">Bent u zeker?</h2>

        <p>
            Je staat op het punt om de geselecteerde bestanden op te ruimen.
            Enkel ongebruikte bestanden zullen worden verwijderd.
        </p>

        <div v-cloak slot="modal-action-buttons">
            <button type="submit" form="selecting" name="type" value="remove" class="btn btn-error">
                Verwijder de selectie
            </button>
        </div>
    </modal>

    @push('custom-scripts-after-vue')
        <script>
            ;(function() {
                let selectAllButton = document.querySelector('input#select-all');
                let assetInputs = document.querySelectorAll('input[name="asset_ids[]"]');

                selectAllButton.addEventListener('click', function() {
                    selectAllButton.checked ? setCheckboxFields(assetInputs, true) : setCheckboxFields(assetInputs, false);
                })

                function setCheckboxFields(checkboxFields, state) {
                    for(var i = 0; i < checkboxFields.length; i++) {
                        checkboxFields[i].checked = state;
                    }
                }
            })();
        </script>
    @endpush
</x-chief::page.template>
