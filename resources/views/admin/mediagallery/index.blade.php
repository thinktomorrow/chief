@extends('chief::layout.master')

@section('page-title', 'Media galerij')

@section('header')
    <div class="container">
        @component('chief::layout._partials.header', [
            'title' => 'Media galerij'
        ])
            @slot('breadcrumbs')
                <a href="{{ route('chief.back.dashboard') }}" title="Ga naar het dashboard" class="link link-primary">
                    <x-chief-icon-label type="back">Dashboard</x-chief-icon-label>
                </a>
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row gutter-3">
            <div class="w-full lg:w-2/3">
                <div class="card">
                    <form method="POST" action="{{ route('chief.mediagallery.bulk') }}" id="selecting">
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

                        <div class="row gutter-3">
                            @foreach($assets as $index => $asset)
                                <div class="w-1/2 xl:w-1/3 2xl:w-1/4">
                                    @include('chief::admin.mediagallery.item')
                                </div>
                            @endforeach
                        </div>
                    </form>

                    <div class="mt-8">
                        {{ $assets->links('chief::pagination.default') }}
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/3">
                <div class="card">
                    <div class="w-full space-x-1 mt-0.5">
                        <span class="text-lg display-base display-dark">
                            Filter
                        </span>
                    </div>

                    <form method="GET" id="filtering" class="space-y-4">
                        <span class="text-grey-500">{{ $assets->total() }} resultaten</span>

                        <x-chief-form::formgroup id="name" label="Bestandsnaam">
                            <input type="text" name="search" placeholder="Zoek op bestandsnaam ..." value="{{ old('search', request()->input('search'))}}">
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
                                <input type="checkbox" name="unused" id="unused" {{ old('unused', request()->input('unused')) ? 'checked' : ''}}>

                                <span>Toon enkel ongebruikte media</span>
                            </label>
                        </x-chief-form::formgroup>

                        <button type="submit" form="filtering" class="btn btn-primary">Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <modal id="mediagallery-bulk-delete-modal" title="Selectie verwijderen">
        <h2 class="h2 display-dark">Bent u zeker?</h2>

        <p>Je staat op het punt om de geselecteerde bestanden op te ruimen. Enkel ongebruikte bestanden zullen worden verwijderd.</p>

        <div v-cloak slot="modal-action-buttons">
            <button type="submit" form="selecting" name="type" value="remove" class="btn btn-error">Verwijder de selectie</button>
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
