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
    <div class="row">
        <div class="column-8">
            <div class="bg-white p-12 rounded-2xl space-y-16">
                @adminCan('fragments-index', $model)
                    <livewire:fragments :owner="$model" />
                @endAdminCan
            </div>
        </div>

        <div class="column">
            <div class="p-12 space-y-10">
                <div>
                    {!! $model->adminLabel('online_status') !!}

                    @adminCan('preview', $model)
                        <a class="block" href="@adminRoute('preview', $model)" target="_blank">Bekijk op site</a>
                    @endAdminCan

                    @foreach(['draft', 'publish', 'unpublish', 'archive', 'unarchive'] as $action)
                        @adminCan($action, $model)
                            @include('chief::back.managers._transitions.' . $action)
                        @endAdminCan
                    @endforeach
                </div>

                @adminCan('links-edit', $model)
                    <livewire:links :model="$model" />
                @endAdminCan

                @adminCan('fields-edit', $model)
                    @foreach($fields->tagged('component')->groupByComponent() as $componentKey => $componentFields)
                        <livewire:fields_component :model="$model" :componentKey="$componentKey" />
                    @endforeach

                    <livewire:fields_component :model="$model"/>
                @endAdminCan

                <div>
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

@include('chief::back._components.file-component')
@include('chief::back._components.filesupload-component')
