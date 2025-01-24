@php
    use Thinktomorrow\Chief\Sites\BelongsToSites;

@endphp
<x-chief::page.template :title="$resource->getPageTitle($model)">
    <x-slot name="hero">
        <x-chief::page.hero :breadcrumbs="[$resource->getPageBreadCrumb('edit')]">
            @if ($forms->has('pagetitle'))
                <x-slot name="customTitle">
                    <x-chief-form::forms id="pagetitle"/>
                </x-slot>
            @else
                <x-slot name="title">
                    {{ $resource->getPageTitle($model) }}
                </x-slot>
            @endif

            @if (count(config('chief.locales')) > 1)
                <x-chief::tabs :listen-for-external-tab="true" class="-mb-3 mt-1">
                    @foreach (config('chief.locales') as $locale)
                        <x-chief::tabs.tab tab-id="{{ $locale }}"></x-chief::tabs.tab>
                    @endforeach
                </x-chief::tabs>
            @endif

            @if($model instanceof BelongsToSites)
                <livewire:chief-wire::resource-sites
                    :resource-key="$resource::resourceKey()"
                    :modelReference="$model->modelReference()"
                    :sites="$model->getSiteIds()"/>
            @endif

            {{--                        @if(count(ChiefLocaleConfig::getLocales()) > 1)--}}
            {{--                            <x-chief::tabs :listen-for-external-tab="true" class="-mb-3">--}}
            {{--                                @foreach(ChiefLocaleConfig::getLocales() as $locale)--}}
            {{--                                    <x-chief::tabs.tab tab-id='{{ $locale }}'></x-chief::tabs.tab>--}}
            {{--                                @endforeach--}}
            {{--                            </x-chief::tabs>--}}
            {{--                        @endif--}}

            @include('chief::manager._partials.edit-actions')
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid>
        <x-chief-form::forms position="main"/>

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
            <x-chief-fragments::index :context-id="$context->id"/>
        </div>
        @endAdminCan

        <x-chief-form::forms position="main-bottom"/>

        <x-slot name="aside">
            <x-chief-form::forms position="aside-top"/>
            <x-chief::window.states/>
            <x-chief::window.links/>
            <x-chief-form::forms position="aside"/>
        </x-slot>
    </x-chief::page.grid>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.template>
