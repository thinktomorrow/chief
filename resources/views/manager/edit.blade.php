<x-chief::page.multisite-template>
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
        <div class="divide-y divide-black/10 [&>*:not(:first-child)]:pt-6 [&>*:not(:last-child)]:pb-6">
            <div class="space-y-4">
                <div class="flex items-start justify-between gap-4">
                    <h2 class="mt-[0.1875rem] text-lg/6 font-medium text-grey-950">Sites</h2>

                    <x-chief-table::button variant="grey" size="sm">
                        <x-chief::icon.quill-write />
                    </x-chief-table::button>
                </div>

                <div class="space-y-1">
                    @foreach ([
                            'thinktomorrow.be' => 'thinktomorrow.be',
                            'thinktomorrow.be/nl' => 'thinktomorrow.be/nl',
                            'thinktomorrow.be/en' => 'thinktomorrow.be/en',
                            'thinktomorrow.be/fr' => 'thinktomorrow.be/fr',
                            'thinktomorrow.be/de' => 'thinktomorrow.be/de'
                        ]
                        as $site => $url)
                        <div class="flex items-start gap-2">
                            <div class="my-1.5">
                                <svg class="size-3 fill-green-500" viewBox="0 0 6 6" aria-hidden="true">
                                    <circle cx="3" cy="3" r="3" />
                                </svg>
                            </div>

                            <div>
                                <p class="leading-6 text-grey-500">{{ $site }}</p>
                                <p class="font-medium leading-6 text-grey-900">/over-ons</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

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
