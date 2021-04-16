@extends('chief::back._layouts.master')

@section('page-title', 'Media galerij')

@component('chief::back._layouts._partials.header')
    @slot('title')
        Media galerij
    @endslot

    @slot('breadcrumbs')
        {{-- TODO: use correct route --}}
        <a href="/admin" class="link link-primary">
            <x-link-label type="back">Ga terug</x-link-label>
        </a>
    @endslot
@endcomponent

@section('content')
    <div class="container">
        <div class="row gutter-6">
            <div class="w-full lg:w-2/3">
                <div class="window window-white space-y-12">
                    <form method="POST" action="{{ route('chief.mediagallery.bulk') }}" id="selecting" class="flex justify-between items-center">
                        <label for="select-all" class="flex items-center text-grey-700 space-x-2 cursor-pointer with-custom-checkbox">
                            <input type="checkbox" name="select_all" id="select-all">
                            <span>Alles selecteren</span>
                        </label>

                        <div class="space-x-2">
                            <button type="submit" form="selecting" name="type" value="download" class="btn btn-primary">Download de selectie</button>
                            <button v-cloak @click="showModal('mediagallery-bulk-delete-modal')" type="button" class="btn btn-error">Verwijder de selectie</button>
                        </div>
                    </form>

                    <div class="row gutter-3">
                        @foreach($assets as $index => $asset)
                            <div class="w-1/3 xl:w-1/4">
                                @include('chief::admin.mediagallery.item')
                            </div>
                        @endforeach
                    </div>

                    <div>
                        {{ $assets->links('chief::manager.pagination') }}
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/3">
                <div class="window window-white space-y-6">
                    <div>
                        <h3>Filteren</h3>

                        <span class="text-grey-500">{{ $assets->total() }} resultaten</span>
                    </div>

                    <form method="GET" id="filtering" class="space-y-6">
                        <div class="space-y-3">
                            <h6>Bestandsnaam</h6>

                            <div>
                                <input
                                    placeholder="Zoek op bestandsnaam ..."
                                    class="input inset-s"
                                    type="text"
                                    name="search"
                                    value="{{ old('search', request()->input('search'))}}"
                                >
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h6>Pagina</h6>

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
                        </div>

                        <label for="unused" class="flex items-center text-grey-700 space-x-2 cursor-pointer with-custom-checkbox">
                            <input type="checkbox" name="unused" id="unused" {{ old('unused', request()->input('unused')) ? 'checked' : ''}}>

                            <span>Toon enkel ongebruikte media</span>
                        </label>

                        <button type="submit" form="filtering" class="btn btn-primary">Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <modal id="mediagallery-bulk-delete-modal" title="Selectie verwijderen">
        <h3>Bent u zeker?</h3>

        <p>Je staat op het punt om de geselecteerde bestanden op te ruimen. Enkel ongebruikte bestanden zullen worden verwijderd.</p>

        <div v-cloak slot="modal-action-buttons">
            <button type="submit" form="selecting" name="type" value="remove" class="btn btn-error-filled">Verwijder de selectie</button>
        </div>
    </modal>
@stop

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
