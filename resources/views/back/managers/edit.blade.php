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
@endcomponent

@section('content')
    <div class="container my">
        <div class="row gutter-6">
            <div class="w-full lg:w-2/3">
                <div class="window window-white space-y-12">
                    @adminCan('fields-edit', $model)
                        <livewire:fields_component title="Algemeen" :model="$model" />
                    @endAdminCan

                    @adminCan('fragments-index', $model)
                        <livewire:fragments :owner="$model" />
                    @endAdminCan
                </div>
            </div>

            <div class="w-full lg:w-1/3">
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
