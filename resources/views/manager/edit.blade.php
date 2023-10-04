@php use Thinktomorrow\Chief\Locale\ChiefLocaleConfig;use Thinktomorrow\Chief\Locale\LocaleRepository;use Thinktomorrow\Chief\Locale\Localisable; @endphp
<x-chief::page.template :title="$resource->getPageTitle($model)">
    <x-slot name="hero">
        <x-chief::page.hero :breadcrumbs="[$resource->getPageBreadCrumb()]">
            @if($forms->has('pagetitle'))
                <x-slot name="customTitle">
                    <x-chief-form::forms id="pagetitle"/>
                </x-slot>
            @else
                <x-slot name="title">
                    {{ $resource->getPageTitle($model) }}
                </x-slot>
            @endif

            @if($resource instanceof LocaleRepository && $model instanceof Localisable)
                <livewire:chief-wire::model-locales
                        :modelReference="$model->modelReference()"
                        :locales="$model->getLocales()"/>
            @endif

            @if(count(ChiefLocaleConfig::getLocales()) > 1)
                <x-chief::tabs :listen-for-external-tab="true" class="-mb-3">
                    @foreach(ChiefLocaleConfig::getLocales() as $locale)
                        <x-chief::tabs.tab tab-id='{{ $locale }}'></x-chief::tabs.tab>
                    @endforeach
                </x-chief::tabs>
            @endif
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid>
        <x-chief-form::forms position="main"/>

        @adminCan('fragments-index', $model)
        <x-chief::fragments :owner="$model"/>
        @endAdminCan

        <x-chief-form::forms position="main-bottom"/>

        <x-slot name="aside">
            <x-chief-form::forms position="aside-top"/>
            <x-chief::window.states/>
            <x-chief::window.links/>
            <x-chief-form::forms position="aside"/>
        </x-slot>
    </x-chief::page.grid>

    @push('custom-scripts')
        @include('chief::layout._partials.editor-script')
    @endpush
</x-chief::page.template>
