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
            @if(!$fields->component('chief-page-title')->isEmpty())
                @slot('hasDefaultTitle', false)
                @slot('title')
                    <livewire:fields_component
                        componentKey="chief-page-title"
                        :model="$model"
                        template="chief::manager.cards.fields.templates.pagetitle"
                    />
                @endslot
            @else
                @slot('title')
                    @adminConfig('pageTitle')
                @endslot
            @endif

            @slot('breadcrumbs')
                @adminCan('index')
                    <a href="@adminRoute('index')" class="link link-primary">
                        <x-icon-label type="back">Terug naar overzicht</x-icon-label>
                    </a>
                @endAdminCan
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row gutter-3">
            <div class="w-full space-y-6 lg:w-2/3">
                @adminCan('fields-edit', $model)
                    @if($fields->component('main')->any())
                        <div class="window window-white">
                            <livewire:fields_component title="Algemeen" :model="$model" componentKey="main" />
                        </div>
                    @endif
                @endAdminCan

                @adminCan('fragments-index', $model)
                    <div class="window window-white">
                        <livewire:fragments :owner="$model" />
                    </div>
                @endAdminCan
            </div>

            <div class="w-full lg:w-1/3">
                <div class="space-y-6">
                    @adminCan('status-edit', $model)
                        <livewire:status :model="$model" class="window window-grey" />
                    @endAdminCan

                    @adminCan('links-edit', $model)
                        <livewire:links :model="$model" class="window window-grey" />
                    @endAdminCan

                    @adminCan('fields-edit', $model)
                        @foreach($fields->tagged('component')->notTagged('chief-component')->notTagged('component-main')->groupByComponent() as $componentKey => $componentFields)
                            <livewire:fields_component
                                :model="$model"
                                :componentKey="$componentKey"
                                :title="$componentKey"
                                class="window window-grey"
                            />
                        @endforeach

                        <!-- default fields - not assigned to a component -->
                        <livewire:fields_component
                            :model="$model"
                            title="Algemeen"
                            class="window window-grey"
                        />
                    @endAdminCan
                </div>
            </div>
        </div>
    </div>
@stop

@push('portals')
    @adminCan('delete', $model)
        @include('chief::manager._transitions.modals.delete-modal')
    @endAdminCan
    @adminCan('archive', $model)
        @include('chief::manager._transitions.modals.archive-modal')
    @endAdminCan
@endpush

@push('custom-scripts-after-vue')
    @include('chief::layout._partials.editor-script')
@endpush

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
