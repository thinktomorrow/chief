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

            @if (count(config('chief.locales')) > 1)
                <x-chief::tabs :listen-for-external-tab="true" class="-mb-3">
                    @foreach (config('chief.locales') as $locale)
                        <x-chief::tabs.tab tab-id="{{ $locale }}"></x-chief::tabs.tab>
                    @endforeach
                </x-chief::tabs>
            @endif

            @include('chief::manager._edit._edit_actions')
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

    @include('chief::layout._partials.editor-script')
</x-chief::page.template>
