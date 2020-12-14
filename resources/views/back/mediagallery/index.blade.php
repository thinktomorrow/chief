@extends('chief::back._layouts.master')

@section('page-title', 'Media galerij')

@section('content')
    <div class="row gutter-l stack-l">
        <div class="column-12">
            <h1>Media galerij</h1>
        </div>

        <div class="column-9">
            <div class="column-12">
                <form method="POST" action="{{ route('chief.mediagallery.bulk') }}" id="selecting" class="gutter">
                    <div class="flex items-center justify-between column-12 formgroup">
                        <div class="inline-block formgroup-input">
                            <label for="select-all" style="cursor: pointer">
                                <input type="checkbox" name="select_all" id="select-all" hidden>
                                <span class="custom-checkbox" style="pointer-events: auto"></span>
                                Alles selecteren
                            </label>
                        </div>

                        <div>
                            <button type="submit" form="selecting" name="type" value="download" class="btn btn-primary">Download de selectie</button>
                            <button v-cloak @click="showModal('mediagallery-bulk-delete-modal')" type="button" class="btn btn-error">Verwijder de selectie</button>
                            <modal id="mediagallery-bulk-delete-modal" class="large-modal" title='Bent u zeker?'>
                                <p>Je staat op het punt om de geselecteerde bestanden op te ruimen. Enkel ongebruikte bestanden zullen worden verwijderd.</p>
                                <div v-cloak slot="modal-action-buttons">
                                    <button type="submit" form="selecting" name="type" value="remove" class="btn btn-error">Verwijder de selectie</button>
                                </div>
                            </modal>
                        </div>
                    </div>

                    <div class="column-12">
                        <div class="row gutter-s">
                            @foreach($assets as $index => $asset)
                                <div class="column-4 xl-column-3">
                                    @include('chief::back.mediagallery.item')
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            <div class="column-12">
                {{ $assets->links('chief::back.managers.pagination') }}
            </div>
        </div>

        <div class="column-3">
            <h2 class="mb-0">Filteren</h2>

            <span class="text-grey-400">{{ $assets->total() }} resultaten</span>

            <form method="GET" id="filtering" class="mt-4 mb-8">
                <div class="stack">
                    <h3 class="mb-0">Bestandsnaam</h3>
                    <input placeholder="Zoek op bestandsnaam ..." class="input inset-s" type="text" name="search" value="{{ old('search', request()->input('search'))}}">
                </div>

                <div class="stack">
                    <h3 class="mb-0">Pagina</h3>
                    <chief-multiselect
                        name="owner"
                        :options='@json($pages)'
                        selected='@json(old('owner', request()->input('owner')))'
                        :multiple='false'
                        grouplabel="group"
                        groupvalues="values"
                        labelkey="label"
                        valuekey="id"
                    >
                    </chief-multiselect>
                </div>

                <div class="formgroup stack">
                    <div class="formgroup-input">
                        <label for="unused" style="cursor: pointer">
                            <input type="checkbox" name="unused" id="unused" {{ old('unused', request()->input('unused')) ? 'checked' : ''}} hidden>
                            <span class="custom-checkbox" style="pointer-events: auto"></span>
                            <svg class="inline-block mr-2" width="16" height="16" fill="currentColor" viewBox="0 0 470 470.288" stroke="none"><path d="m323.109375 339.621094c-4.097656 0-8.171875-1.558594-11.308594-4.671875-6.25-6.25-6.269531-16.382813-.019531-22.632813l93.71875-93.910156c20.628906-20.503906 31.976562-47.894531 31.976562-77.183594 0-60.226562-48.875-109.226562-108.949218-109.226562-29.226563 0-56.53125 11.347656-76.925782 31.976562l-93.824218 94.039063c-6.253906 6.269531-16.386719 6.25-22.636719.042969-6.25-6.253907-6.25-16.386719-.019531-22.636719l93.78125-93.972657c26.386718-26.710937 61.78125-41.429687 99.625-41.429687 77.71875 0 140.949218 63.359375 140.949218 141.226563 0 37.886718-14.699218 73.34375-41.386718 99.839843l-93.652344 93.847657c-3.136719 3.132812-7.210938 4.691406-11.328125 4.691406zm0 0"/><path d="m141.09375 470.289062c-77.71875 0-140.949219-63.359374-140.949219-141.226562 0-37.890625 14.699219-73.34375 41.386719-99.863281l56.808594-56.894531c6.230468-6.292969 16.363281-6.273438 22.636718-.042969 6.25 6.25 6.25 16.382812.019532 22.632812l-56.851563 56.984375c-20.652343 20.476563-32 47.871094-32 77.183594 0 60.222656 48.875 109.226562 108.949219 109.226562 29.203125 0 56.511719-11.351562 76.925781-32l56.898438-57.023437c6.230469-6.296875 16.363281-6.273437 22.632812-.042969 6.25 6.25 6.25 16.382813.023438 22.632813l-56.855469 56.980469c-26.386719 26.730468-61.800781 41.453124-99.625 41.453124zm0 0"/><path d="m234.8125 251.152344c-4.097656 0-8.171875-1.558594-11.308594-4.671875-6.25-6.25-6.25-16.382813-.019531-22.636719l85.332031-85.523438c6.230469-6.292968 16.363282-6.25 22.632813-.042968 6.253906 6.25 6.253906 16.382812.023437 22.632812l-85.335937 85.527344c-3.132813 3.15625-7.230469 4.714844-11.324219 4.714844zm0 0"/><path d="m149.476562 336.675781c-4.09375 0-8.167968-1.554687-11.304687-4.671875-6.25-6.25-6.273437-16.382812-.023437-22.632812l47.574218-47.679688c6.230469-6.292968 16.363282-6.25 22.632813-.042968 6.253906 6.25 6.273437 16.382812.023437 22.632812l-47.574218 47.679688c-3.136719 3.136718-7.230469 4.714843-11.328126 4.714843zm0 0"/><path d="m453.476562 470.289062c-4.09375 0-8.191406-1.558593-11.328124-4.714843l-437.332032-438.273438c-6.230468-6.25-6.230468-16.382812.019532-22.632812 6.253906-6.210938 16.386718-6.253907 22.636718.042969l437.332032 438.292968c6.230468 6.25 6.230468 16.382813-.019532 22.632813-3.136718 3.09375-7.210937 4.652343-11.308594 4.652343zm0 0"/></svg>
                            <span class="mr-2">Toon enkel ongebruikte media</span>
                        </label>
                    </div>
                </div>

                <button type="submit" form="filtering" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>


@stop

@section('chief-footer')
    @include('chief::back._layouts._partials.chief-footer')
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
