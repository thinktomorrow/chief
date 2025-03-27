<x-chief::page.template :title="$resource->getPageTitle($model)">
    <x-slot name="header">
        <x-chief::page.header
            :title="$resource->getPageTitle($model)"
            :breadcrumbs="[
                ['label' => $resource->getIndexTitle($model), 'url' => $manager->route('index', $model), 'icon' => $resource->getNavItem()?->icon()],
                $resource->getPageTitle($model)
            ]"
        >
            @if ($forms->has('pagetitle'))
                <x-slot name="customTitle">
                    <x-chief-form::forms id="pagetitle" />
                </x-slot>
            @endif

            <x-slot name="actions">
                @if ($model instanceof \Thinktomorrow\Chief\Sites\BelongsToSites)
                    <livewire:chief-wire::site-tabs :model="$model" />
                @endif

                @include('chief::manager._partials.edit-actions')
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <x-chief-form::forms position="main" />

    @if ($model instanceof \Thinktomorrow\Chief\Fragments\ContextOwner)
        <livewire:chief-fragments::contexts :resource-key="$resource::resourceKey()" :model="$model" />
    @endif

    <x-chief-form::forms position="main-bottom" />

    <x-slot name="sidebar">
        <x-chief-form::forms position="aside-top" />

        @if ($model instanceof \Thinktomorrow\Chief\Sites\BelongsToSites && $model instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable)
            <livewire:chief-wire::site-links :model="$model" />
        @elseif ($model instanceof \Thinktomorrow\Chief\Sites\BelongsToSites)
            <livewire:chief-wire::sites :model="$model" />
        @endif

        <x-chief-form::forms position="aside" />
    </x-slot>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.template>
