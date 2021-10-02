@extends('chief::layout.master')

@push('custom-styles')
    @livewireStyles
@endpush

@push('custom-scripts')
    @livewireScripts
@endpush

@section('page-title')
    @adminConfig('pageTitle')
@endsection

@section('header')
    <div class="container">
        @component('chief::layout._partials.header', [ 'hasDefaultTitle' => false ])
            @if($fields->tagged(\Thinktomorrow\Chief\ManagedModels\Fields\Fields::PAGE_TITLE_TAG)->any())
                @slot('title')
                    <livewire:fields_component
                        componentKey="{{ \Thinktomorrow\Chief\ManagedModels\Fields\Fields::PAGE_TITLE_TAG }}"
                        :model="$model"
                        template="chief::manager.windows.fields.templates.pagetitle"
                    />
                @endslot
            @else
                @slot('title')
                    <h1 class="text-grey-900">
                        @adminConfig('pageTitle')
                    </h1>
                @endslot
            @endif

            @slot('breadcrumbs')
                @if($model->adminConfig()->getBreadCrumb())
                    <a href="{{ $model->adminConfig()->getBreadCrumb()->url }}" class="link link-primary">
                        <x-chief-icon-label type="back">{{ $model->adminConfig()->getBreadCrumb()->label }}</x-chief-icon-label>
                    </a>
                @else
                    @adminCan('index')
                        <a href="@adminRoute('index')" class="link link-primary">
                            <x-chief-icon-label type="back">Terug naar overzicht</x-chief-icon-label>
                        </a>
                    @endAdminCan
                @endif
            @endslot
        @endcomponent
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row gutter-3">
            <div class="w-full space-y-6 lg:w-2/3">
                @adminCan('fields-edit', $model)
                    @foreach($fields->getWindowsByPosition('top') as $fieldWindow)
                        @include('chief::manager.windows.show')
                    @endforeach
                @endAdminCan

                @adminCan('fragments-index', $model)
                    <livewire:fragments
                        :owner="$model"
                        class="window window-white window-sm"
                    />
                @endAdminCan

                @adminCan('fields-edit', $model)
                    @foreach($fields->getWindowsByPosition('bottom') as $fieldWindow)
                        @include('chief::manager.windows.show')
                    @endforeach
                @endAdminCan
            </div>

            <div class="w-full lg:w-1/3">
                <div class="space-y-6">
                    @adminCan('status-edit', $model)
                        <livewire:status :model="$model" class="window window-grey window-md" />
                    @endAdminCan

                    @adminCan('links-edit', $model)
                        <livewire:links :model="$model" class="window window-grey window-md" />
                    @endAdminCan

                    @adminCan('fields-edit', $model)
                        {{-- FieldWindows without a specific position (default position: sidebar)  --}}
                        @foreach($fields->getWindowsByPosition('sidebar') as $fieldWindow)
                            @include('chief::manager.windows.show')
                        @endforeach

                        {{-- Fields without a dedicated FieldWindow --}}
                        <livewire:fields_component
                            :model="$model"
                            title="Algemeen"
                            class="window window-grey window-md"
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
