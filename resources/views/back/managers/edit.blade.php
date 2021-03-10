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
        @if(!$fields->component('chief-page-title')->isEmpty())
            <livewire:fields_component
                componentKey="chief-page-title"
                :model="$model"
                template="chief::manager.cards.fields.templates.pagetitle" />
        @else
            {{ $model->adminLabel('title') }}
        @endif
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
    <div class="row -m-6">
        <div class="xs-column-12 s-column-12 m-column-12 l-column-8 p-6">
            <div class="window window-white space-y-12">
                @adminCan('fields-edit', $model)
                    <livewire:fields_component title="Algemeen" :model="$model" />
                @endAdminCan

                @adminCan('fragments-index', $model)
                    <livewire:fragments :owner="$model" />
                @endAdminCan
            </div>
        </div>

        <div class="xs-column-12 s-column-12 m-column-12 l-column-4 p-6">
            <div class="space-y-12">

                @adminCan('links-edit', $model)
                    <livewire:links :model="$model" class="window window-grey" />
                @endAdminCan

                @adminCan('fields-edit', $model)
                    @foreach($fields->tagged('component')->notTagged('chief-component')->groupByComponent() as $componentKey => $componentFields)
                        <livewire:fields_component :model="$model" :componentKey="$componentKey" class="window window-grey" />
                    @endforeach
                @endAdminCan

                @adminCan('delete', $model)
                    @include('chief::back.managers._transitions.delete')
                @endAdminCan
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
