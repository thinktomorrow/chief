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
            <div class="w-full lg:w-2/3 space-y-6">
                @adminCan('fields-edit', $model)
                @if($fields->component('main')->any())
                    <div class="window window-white">
                        <h3>Algemeen</h3>
                        @foreach($fields->component('main')->groupByComponent() as $componentKey => $componentFields)
                            <livewire:fields_component
                                    :model="$model"
                                    :componentKey="$componentKey"
                            />
                        @endforeach
                    </div>

                @endif

                @endAdminCan

                <!-- TEST -->
                <div class="window window-white">
                    <div id="refresh-test">
                        {{ date('H:i:s') }}
                        <a data-sidebar-trigger href="/admin/article/create">open sidebar</a>
                        <a data-sidebar-trigger data-sidebar-refresh="#refresh-test" href="/admin/article/create">open sidebar</a>
                    </div>
                </div>

                <div class="window window-white">
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
                                :title="$componentKey"
                                class="window window-grey" />
                        @endforeach

                        <!-- default fields - not assigned to a component -->
                        <livewire:fields_component
                                :model="$model"
                                title="Algemeen"
                                class="window window-grey"
                        />
                    @endAdminCan
                </div>
                <div class="window">
                    <div class="flex flex-col space-y-3">
                        @foreach(['archive', 'unarchive', 'delete'] as $action)
                            @adminCan($action, $model)
                                @include('chief::manager._transitions.index.'. $action, [ 'style' => 'link' ])
                            @endAdminCan
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('custom-scripts-after-vue')
    @include('chief::layout._partials.editor-script')
@endpush

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
