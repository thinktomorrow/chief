@php
    $title = ucfirst($resource->getLabel());
@endphp

<x-chief::page.template :title="$title">
    <x-slot name="hero">
        <x-chief::page.hero :title="$title" :breadcrumbs="[$resource->getPageBreadCrumb()]" class="max-w-3xl">
            <button form="createForm" type="submit" class="btn btn-primary">Aanmaken</button>
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid class="max-w-3xl">
        <div class="card">
            <form id="createForm" method="POST" action="@adminRoute('store')" enctype="multipart/form-data" role="form">
                @csrf

                <div class="space-y-6" x-data="{}">
                    <x-chief-form::fields not-tagged="edit,not-on-create"/>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="btn btn-primary">Aanmaken</button>

                        <button type="button" x-on:click="$dispatch('open-dialog', { id: 'duplicate-modal-on-create'})">
                            <x-chief::link>
                                Of kopieer een bestaande...
                            </x-chief::link>
                        </button>
                    </div>


                    <template x-teleport="body">
                        <x-chief::dialog id="duplicate-modal-on-create" title="Kies een {{ $model->getLabel() }} om te kopiëren" size="xs">
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

                </div>
            </form>

        </div>
    </x-chief::page.grid>


    {{--    @adminCan('duplicate', $model)--}}

    {{--    @endAdminCan--}}

</x-chief::page.template>


@push('custom-scripts')
    @include('chief::layout._partials.editor-script')
@endpush
