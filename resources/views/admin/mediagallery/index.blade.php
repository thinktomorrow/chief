<x-chief::page.template title="Media">
    <x-chief::page.grid>
{{--        <form method="POST" action="{{ route('chief.mediagallery.bulk') }}" id="selecting" class="card">--}}
{{--            <div class="flex items-center justify-between mb-4">--}}
{{--                <x-chief::input.label for="select-all" class="flex items-start gap-2 form-light">--}}
{{--                    <x-chief::input.checkbox id="select-all" name="select_all"/>--}}
{{--                    <span class="body body-dark">Alles selecteren</span>--}}
{{--                </x-chief::input.label>--}}

{{--                <div class="space-x-2">--}}
{{--                    <button type="submit" form="selecting" name="type" value="download" class="btn btn-primary">Download de selectie</button>--}}
{{--                    <button v-cloak @click="showModal('mediagallery-bulk-delete-modal')" type="button" class="btn btn-error-outline">Verwijder de selectie</button>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </form>--}}

        <livewire:chief-wire::file-gallery />


{{--        <x-slot name="aside">--}}
{{--            <x-chief::window title="Filter" class="card">--}}
{{--                <form method="GET" id="filtering" class="space-y-4">--}}
{{--                    <span class="text-grey-500">{{ $assets->total() }} resultaten</span>--}}

{{--                    <x-chief::input.group>--}}
{{--                        <x-chief::input.label for="search">Bestandsnaam</x-chief::input.label>--}}
{{--                        <x-chief::input.text--}}
{{--                            id="search"--}}
{{--                            name="search"--}}
{{--                            placeholder="Zoek op bestandsnaam ..."--}}
{{--                            value="{{ old('search', request()->input('search'))}}"--}}
{{--                        />--}}
{{--                    </x-chief::input.group>--}}

{{--                    <x-chief::input.group>--}}
{{--                        <x-chief::input.label for="owner">Pagina</x-chief::input.label>--}}
{{--                        <chief-multiselect--}}
{{--                            id="owner"--}}
{{--                            name="owner"--}}
{{--                            :options='@json($pages)'--}}
{{--                            selected='@json(old('owner', request()->input('owner')))'--}}
{{--                            :multiple='false'--}}
{{--                            grouplabel="group"--}}
{{--                            groupvalues="values"--}}
{{--                            labelkey="label"--}}
{{--                            valuekey="id"--}}
{{--                        />--}}
{{--                    </x-chief::input.group>--}}

{{--                    <x-chief::input.group inner-class="flex items-start gap-2">--}}
{{--                        <x-chief::input.checkbox--}}
{{--                            id="unused"--}}
{{--                            name="unused"--}}
{{--                            :checked="old('unused', request()->input('unused'))"--}}
{{--                        />--}}

{{--                        <x-chief::input.label for="unused" class="body-dark" unset>--}}
{{--                            Toon enkel ongebruikte media--}}
{{--                        </x-chief::input.label>--}}
{{--                    </x-chief::input.group>--}}

{{--                    <button type="submit" form="filtering" class="btn btn-primary">Filter</button>--}}
{{--                </form>--}}
{{--            </x-chief::window>--}}
{{--        </x-slot>--}}
    </x-chief::page.grid>

{{--    <modal id="mediagallery-bulk-delete-modal" title="Selectie verwijderen">--}}
{{--        <h2 class="h2 h1-dark">Bent u zeker?</h2>--}}

{{--        <p>--}}
{{--            Je staat op het punt om de geselecteerde bestanden op te ruimen.--}}
{{--            Enkel ongebruikte bestanden zullen worden verwijderd.--}}
{{--        </p>--}}

{{--        <div v-cloak slot="modal-action-buttons">--}}
{{--            <button type="submit" form="selecting" name="type" value="remove" class="btn btn-error">--}}
{{--                Verwijder de selectie--}}
{{--            </button>--}}
{{--        </div>--}}
{{--    </modal>--}}

    @push('custom-scripts-after-vue')
{{--        <script>--}}
{{--            ;(function() {--}}
{{--                let selectAllButton = document.querySelector('input#select-all');--}}
{{--                let assetInputs = document.querySelectorAll('input[name="asset_ids[]"]');--}}

{{--                selectAllButton.addEventListener('click', function() {--}}
{{--                    selectAllButton.checked ? setCheckboxFields(assetInputs, true) : setCheckboxFields(assetInputs, false);--}}
{{--                })--}}

{{--                function setCheckboxFields(checkboxFields, state) {--}}
{{--                    for(var i = 0; i < checkboxFields.length; i++) {--}}
{{--                        checkboxFields[i].checked = state;--}}
{{--                    }--}}
{{--                }--}}
{{--            })();--}}
{{--        </script>--}}
    @endpush
</x-chief::page.template>
