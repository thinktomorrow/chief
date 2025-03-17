@php
    $sites = [
        'thinktomorrow.be' => 'thinktomorrow.be',
        'thinktomorrow.be/nl' => 'thinktomorrow.be/nl',
        'thinktomorrow.be/en' => 'thinktomorrow.be/en',
        'thinktomorrow.be/fr' => 'thinktomorrow.be/fr',
        'thinktomorrow.be/de' => 'thinktomorrow.be/de',
    ];
@endphp

<x-chief::page.multisite-template container="2xl">
    <x-slot name="hero">
        <h1 class="h1 h1-dark">{{ $resource->getPageTitle($model) }}</h1>
    </x-slot>

    <div class="space-y-6">
        <x-chief-form::forms position="main" />

        @adminCan('fragments-index', $model)
        <x-chief::fragments :owner="$model" />
        @endAdminCan

        <x-chief-form::forms position="main-bottom" />
    </div>

    <x-slot name="sidebar">
        <div class="space-y-8">
            <x-chief::window title="Sites">
                <div class="space-y-1">
                    @foreach ($sites as $site)
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-start gap-2">
                                <div class="my-1.5">
                                    <svg class="size-3 fill-green-500" viewBox="0 0 6 6" aria-hidden="true">
                                        <circle cx="3" cy="3" r="3" />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-sm leading-6 text-grey-500">{{ $site }}</p>
                                    <p class="leading-6 text-grey-700">/over-ons</p>
                                </div>
                            </div>

                            <x-chief-table::badge>Default</x-chief-table::badge>
                        </div>
                    @endforeach
                </div>
            </x-chief::window>

            <x-chief-form::forms position="aside" />
        </div>
    </x-slot>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.multisite-template>

{{--
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
    <x-chief::tabs :listen-for-external-tab="true" class="-mb-3 mt-1">
    @foreach (config('chief.locales') as $locale)
    <x-chief::tabs.tab tab-id="{{ $locale }}"></x-chief::tabs.tab>
    @endforeach
    </x-chief::tabs>
    @endif
    
    @include('chief::manager._partials.edit-actions')
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
    
    @include('chief::templates.page._partials.editor-script')
    </x-chief::page.template>
--}}
