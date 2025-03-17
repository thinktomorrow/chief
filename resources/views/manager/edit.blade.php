<x-chief::page.multisite-template :title="$resource->getPageTitle($model)">
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

    <div class="space-y-6">
        <x-chief-form::forms position="main" />

        @if($model instanceof \Thinktomorrow\Chief\Fragments\ContextOwner)
            <livewire:chief-fragments::contexts :resource-key="$resource::resourceKey()" :model="$model" />
        @endif

        <x-chief-form::forms position="main-bottom" />
    </div>

    <x-slot name="sidebar">
        <div class="space-y-8">

            @if($model instanceof BelongsToSites)
                <livewire:chief-wire::site-links :resource-key="$resource::resourceKey()" :model="$model" />
            @endif

            <x-chief::window title="Sites">
                <div class="space-y-1">
                    @foreach ([] as $site)
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-start gap-2">
                                <div class="relative my-1 flex size-4 items-center justify-center p-1">
                                    <div
                                        @class([
                                            'absolute inset-0 animate-pulse rounded-full',
                                            'bg-green-200' => $site['status'] === 'online',
                                            'bg-grey-200' => $site['status'] === 'offline',
                                        ])
                                    ></div>

                                    <svg
                                        @class([
                                            'relative size-2',
                                            'fill-green-500' => $site['status'] === 'online',
                                            'fill-grey-400' => $site['status'] === 'offline',
                                        ])
                                        viewBox="0 0 6 6"
                                        aria-hidden="true"
                                    >
                                        <circle cx="3" cy="3" r="3" />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-sm leading-6 text-grey-500">{{ $site['url'] }}</p>
                                    <p class="leading-6 text-grey-700">/over-ons</p>
                                </div>
                            </div>

                            <x-chief-table::badge>Default</x-chief-table::badge>
                        </div>
                    @endforeach
                </div>
            </x-chief::window>

            <x-chief-form::forms position="aside-top" />


            {{--            <x-chief::window.states />--}}
            {{--            <x-chief::window.links />--}}
            <x-chief-form::forms position="aside" />
        </div>
    </x-slot>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.multisite-template>
