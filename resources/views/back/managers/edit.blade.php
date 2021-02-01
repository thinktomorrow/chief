@extends('chief::back._layouts.master')

@push('custom-styles')
    <livewire:styles />
@endpush

@push('custom-scripts')
    @livewireScripts
@endpush

@section('page-title')
    {{ $model->adminLabel('title') }}
@endsection

@component('chief::back._layouts._partials.header')
    @slot('title')
        {{ $model->adminLabel('title') }}
    @endslot

    @slot('subtitle')
        @adminCan('index')
            <div class="inline-block">
                <a class="center-y" href="@adminRoute('index')">
                    <svg width="24" height="24" class="mr-4"><use xlink:href="#arrow-left"/></svg>
                </a>
            </div>
        @endAdminCan
    @endslot

    <div class="inline-group-s flex items-center">
        {!! $model->adminLabel('online_status') !!}

        @adminCan('update')
            <button data-submit-form="updateForm{{ $model->getMorphClass().'_'.$model->id }}" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
        @endAdminCan

        @include('chief::back.managers._index._options')
    </div>

@endcomponent

@section('content')
    <x-chief-sidebar>
        <div id="fragmentModal"></div>
    </x-chief-sidebar>

    <div class="row gutter-l stack">
        <div class="column-8">
            @adminCan('fragments-index', $model)
                <livewire:fragments :owner="$model" />
            @endAdminCan

            <form
                method="POST"
                action="@adminRoute('update', $model)"
                id="updateForm{{ $model->getMorphClass().'_'.$model->id }}"
                enctype="multipart/form-data"
                role="form"
            >
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">

                @foreach($fields as $field)
                    @formgroup
                        @slot('label',$field->getLabel())
                        @slot('description',$field->getDescription())
                        @slot('isRequired', $field->required())

                        {!! $field->render(get_defined_vars()) !!}
                    @endformgroup
                @endforeach

                <div class="stack text-right">
                    <button
                        data-submit-form="updateForm{{ $model->getMorphClass().'_'.$model->id }}"
                        type="submit"
                        form="updateForm{{ $model->getMorphClass().'_'.$model->id }}"
                        class="btn btn-primary"
                    >
                        Wijzigingen opslaan
                    </button>
                </div>
            </form>
        </div>

        <div class="column">
            @include('chief::managers.partials.links')
        </div>
    </div>
@stop

@push('custom-scripts-after-vue')
    @adminCan('asyncRedactorFileUpload', $model)
        @include('chief::back._layouts._partials.editor-script', ['imageUploadUrl' => $manager->route('asyncRedactorFileUpload', $model)])
    @endAdminCan

    <script>
        // --------------------------------------------------------------------------------
        // FRAGMENT JS --------------------------------------------------------------------
        // --------------------------------------------------------------------------------
        document.addEventListener('DOMContentLoaded', function() {
            const modalContainer = document.getElementById('fragmentModal'),
                  sidebar = new Sidebar('[data-sidebar]'),
                  livewireComponent = Livewire.find(document.getElementById('js-fragments-component').getAttribute('wire:id'));

            const editRequestHandler = function(event) {
                event.preventDefault();
                loadUrlContent(this.getAttribute('href'), modalContainer);
            }

            function listenForEditRequests() {
                const els = document.querySelectorAll('[data-edit-modal]');

                Array.from(els).forEach(function(el) {
                    el.removeEventListener('click', editRequestHandler)
                    el.addEventListener('click', editRequestHandler);
                });
            }

            function listenForFormSubmits() {
                const form = modalContainer.querySelector('form');

                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    fetch(this.action, {
                        method: this.method,
                        body: new FormData(this),
                    })
                    .then(response => { return response.json() })
                    .then(data => {
                        // trigger immediate reload of fragments component
                        livewireComponent.reload();

                        sidebar.close();
                    })
                    .catch(error => {
                        console.log(error);
                    });
                });
            }

            function loadUrlContent(url, container)
            {
                fetch(url)
                .then(response => { return response.text() })
                .then(data => {
                    container.innerHTML = data;

                    // only mount Vue on our vue specific fields and not on the form element itself
                    // so that the submit event still works. I know this is kinda hacky.
                    new Vue({ el: container.querySelector('[data-vue-fields]')});

                    listenForEditRequests();

                    console.log('reload content');

                    listenForFormSubmits();

                    sidebar.open();
                })
                .catch(error => {
                    console.log(error);
                });
            }

            Livewire.on('fragmentsReloaded', () => {
                listenForEditRequests();
            })

            listenForEditRequests();
        });
    </script>
@endpush

@include('chief::back._components.file-component')
@include('chief::back._components.filesupload-component')
