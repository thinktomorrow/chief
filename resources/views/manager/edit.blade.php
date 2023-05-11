<x-chief::page.template :title="$resource->getPageTitle($model)">
    <x-slot name="hero">
        <x-chief::page.hero :breadcrumbs="[$resource->getPageBreadCrumb()]">
            @if($forms->has('pagetitle'))
                <x-slot name="customTitle">
                    <x-chief-form::forms id="pagetitle" />
                </x-slot>
            @else
                <x-slot name="title">
                    {{ $resource->getPageTitle($model) }}
                </x-slot>
            @endif

            @if(count(config('chief.locales')) > 1)
                <tabs class="-mb-3">
                    @foreach(config('chief.locales') as $locale)
                        <tab v-cloak id="{{ $locale }}" name="{{ $locale }}"></tab>
                    @endforeach
                </tabs>
            @endif
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid>
        <x-chief-form::forms position="main" />

        @adminCan('fragments-index', $model)
            <x-chief::fragments :owner="$model" />
        @endAdminCan

        <x-chief-form::forms position="main-bottom" />

        <x-slot name="aside">
            <x-chief-form::forms position="aside-top" />
            <x-chief::window.states />
            <x-chief::window.links />
            <x-chief-form::forms position="aside" />
        </x-slot>
    </x-chief::page.grid>

    @push('portals')
        @adminCan('delete', $model)
            @include('chief::manager._transitions.modals.delete-modal')
        @endAdminCan

        @adminCan('archive', $model)
            @include('chief::manager._transitions.modals.archive-modal')
        @endAdminCan
    @endpush

    @include('chief::components.file-component')
    @include('chief::components.filesupload-component')

    @push('custom-scripts-after-vue')
        @include('chief::layout._partials.editor-script')
    @endpush
</x-chief::page.template>
