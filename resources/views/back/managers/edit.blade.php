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

    @slot('breadcrumbs')
        @adminCan('index')
            <div>
                <a href="@adminRoute('index')" class="link link-primary">
                    <x-link-label type="back">Ga terug</x-link-label>
                </a>
            </div>
        @endAdminCan
    @endslot

    {{-- @slot('subtitle')
        @adminCan('index')
            <div class="inline-block">
                <a class="center-y" href="@adminRoute('index')">
                    <svg width="24" height="24" class="mr-4"><use xlink:href="#arrow-left"/></svg>
                </a>
            </div>
        @endAdminCan
    @endslot --}}

{{--    <div class="inline-group-s flex items-center">--}}
{{--        {!! $model->adminLabel('online_status') !!}--}}

{{--        @adminCan('update')--}}
{{--            <button data-submit-form="updateForm{{ $model->getMorphClass().'_'.$model->id }}" type="button" class="btn btn-primary">Wijzigingen opslaan</button>--}}
{{--        @endAdminCan--}}

{{--        @include('chief::back.managers._index._options')--}}
{{--    </div>--}}
@endcomponent

@section('content')
    <div class="container">
        <div class="row gutter-6">
            <div class="w-full lg:w-2/3">
                <div class="window window-white space-y-12">
                    @adminCan('fields-edit', $model)
                        <livewire:fields_component :model="$model" />
                    @endAdminCan

                    @adminCan('fragments-index', $model)
                        <livewire:fragments :owner="$model" />
                    @endAdminCan
                </div>
            </div>

            <div class="w-full lg:w-1/3">
                <div class="space-y-12">
                    <div class="window window-grey">
                        @adminCan('links-edit', $model)
                            <livewire:links :model="$model" />
                        @endAdminCan
                    </div>

                    <div class="window window-grey">
                        @component('chief::components.card', [
                            'title' => 'SEO',
                            'edit_request_url' => '#',
                            'type' => 'seo'
                        ])
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <h6 class="mb-0">Titel</h6>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>
                                </div>

                                <div class="space-y-2">
                                    <h6 class="mb-0">Description</h6>
                                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Itaque sequi eveniet, dicta, quibusdam nam quis repellendus fuga mollitia, voluptate aspernatur quia eaque deserunt iure aliquid.</p>
                                </div>

                                <div class="space-y-2">
                                    <h6 class="mb-0">Afbeelding</h6>
                                    <img
                                        class="w-24 h-24 object-cover rounded-xl"
                                        src="https://images.unsplash.com/photo-1489533119213-66a5cd877091?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=1951&q=80"
                                        alt="SEO image"
                                    >
                                </div>
                            </div>
                        @endcomponent
                    </div>

                    @adminCan('fields-edit', $model)
                        @foreach($fields->tagged('component')->groupByComponent() as $componentKey => $componentFields)
                            <div class="window window-grey">
                                <livewire:fields_component :model="$model" :componentKey="$componentKey" />
                            </div>
                        @endforeach
                    @endAdminCan

                    @adminCan('delete', $model)
                        @include('chief::back.managers._transitions.delete')
                    @endAdminCan
                </div>
            </div>
        </div>
    </div>
@stop

@push('custom-scripts-after-vue')
     @adminCan('asyncRedactorFileUpload', $model)
        @include('chief::back._layouts._partials.editor-script', [
            'imageUploadUrl' => $manager->route('asyncRedactorFileUpload', $model)
        ])
     @endAdminCan
@endpush

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
