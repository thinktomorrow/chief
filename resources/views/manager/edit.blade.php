@php
    use Thinktomorrow\Chief\Sites\BelongsToSites;

@endphp
<x-chief::page.template :title="$resource->getPageTitle($model)">
    <x-slot name="hero">
        <x-chief::page.hero :breadcrumbs="[$resource->getPageBreadCrumb('edit')]">
            @if ($forms->has('pagetitle'))
                <x-slot name="customTitle">
                    <x-chief-form::forms id="pagetitle" />
                </x-slot>
            @else
                <x-slot name="title">
                    {{ $resource->getPageTitle($model) }}
                </x-slot>
            @endif

            @if($model instanceof BelongsToSites)
                <livewire:chief-wire::site-tabs :resource-key="$resource::resourceKey()" :model="$model" />
            @endif

            @include('chief::manager._partials.edit-actions')
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid>
        <x-chief-form::forms position="main" />

        <livewire:chief-fragments::contexts :resource-key="$resource::resourceKey()" :model="$model" />

        @adminCan('fragments-index', $model)
        <div x-data='{
            contexts: @json($contextsForSwitch)
        }'
             {{-- refresh the fragments window when locale tabs change --}}
             x-on:chieftab.window="(e) => {
                if(e.detail.reference === 'modelLocalesTabs') {
                     const matchingContext = $data.contexts.find((context) => context.locale == e.detail.id);

                     if(matchingContext) {
                        $dispatch('chief-refresh-form', {selector: '[data-fragments-window]', refreshUrl: matchingContext.refreshUrl});
                     }
                }
            }"
        >
            {{--            <x-chief-fragments::index :context-id="$context->id" />--}}
        </div>
        @endAdminCan

        <x-chief-form::forms position="main-bottom" />

        <x-slot name="aside">
            <x-chief-form::forms position="aside-top" />

            @if($model instanceof BelongsToSites)
                <livewire:chief-wire::site-links :resource-key="$resource::resourceKey()" :model="$model" />
            @endif
            {{--            <x-chief::window.states />--}}
            {{--            <x-chief::window.links />--}}
            <x-chief-form::forms position="aside" />
        </x-slot>
    </x-chief::page.grid>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.template>
