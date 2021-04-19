@extends('chief::layout.master')

@push('custom-styles')
    <livewire:styles />
@endpush

@push('custom-scripts')
    @livewireScripts
@endpush

@section('page-title')
    @adminConfig('pageTitle')
@endsection

@section('header')
    <div class="container">
        @component('chief::layout._partials.header')
            @slot('title')
                @if(!$fields->component('chief-page-title')->isEmpty())
                    <livewire:fields_component
                        componentKey="chief-page-title"
                        :model="$model"
                        template="chief::manager.cards.fields.templates.pagetitle"
                    />
                @else
                    @adminConfig('pageTitle')
                @endif
            @endslot

            @slot('breadcrumbs')
                @adminCan('index')
                    <div>
                        <a href="@adminRoute('index')" class="link link-primary">
                            <x-icon-label type="back">Ga terug</x-icon-label>
                        </a>
                    </div>
                @endAdminCan
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row gutter-3">
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
                <div class="space-y-6">
                    @adminCan('links-edit', $model)
                        <livewire:links :model="$model" class="window window-grey" />
                    @endAdminCan

                    @adminCan('fields-edit', $model)
                        @foreach($fields->tagged('component')->notTagged('chief-component')->groupByComponent() as $componentKey => $componentFields)
                            <livewire:fields_component
                                :model="$model"
                                :componentKey="$componentKey"
                                class="window window-grey" />
                        @endforeach
                    @endAdminCan

                    @adminCan('delete', $model)
                        @include('chief::manager._transitions.delete')
                    @endAdminCan
                </div>
            </div>
        </div>
    </div>
@stop

@push('custom-scripts-after-vue')
     @adminCan('asyncRedactorFileUpload', $model)
        @include('chief::layout._partials.editor-script', [
            'imageUploadUrl' => $manager->route('asyncRedactorFileUpload', $model)
        ])
     @endAdminCan
@endpush

@include('chief::components.file-component')
@include('chief::components.filesupload-component')