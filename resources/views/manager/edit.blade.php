@php
    use Thinktomorrow\Chief\Sites\MultiSiteable;

    $contextsForSwitch = app(\Thinktomorrow\Chief\Fragments\Models\ContextRepository::class)->getOrCreateByOwner($model)->map(function($context){ return [
            'id' => $context->id,
            'locale' => $context->locale,
            'refreshUrl' => route('chief::fragments.refresh-index', $context->id)
        ];
    });

@endphp
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

            @if($model instanceof MultiSiteable)
                <livewire:chief-wire::model-locales
                    :resource-key="$resource::resourceKey()"
                    :modelReference="$model->modelReference()"
                    :locales="$model->getLocales()"/>
            @endif

            {{--                        @if(count(ChiefLocaleConfig::getLocales()) > 1)--}}
            {{--                            <x-chief::tabs :listen-for-external-tab="true" class="-mb-3">--}}
            {{--                                @foreach(ChiefLocaleConfig::getLocales() as $locale)--}}
            {{--                                    <x-chief::tabs.tab tab-id='{{ $locale }}'></x-chief::tabs.tab>--}}
            {{--                                @endforeach--}}
            {{--                            </x-chief::tabs>--}}
            {{--                        @endif--}}

            @include('chief::manager._edit._edit_actions')

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
            <x-chief-fragments::index :context-id="$context->id"
                                      locale="{{ count($model->getLocales()) > 0 ? $model->getLocales()[0] : null }}"/>
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

    @push('custom-scripts')
        @include('chief::layout._partials.editor-script')
    @endpush
</x-chief::page.template>
