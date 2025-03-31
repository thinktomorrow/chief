<x-chief::page.template :title="$resource->getPageTitle($model)">
    <x-slot name="header">
        <x-chief::page.header
            :title="$resource->getPageTitle($model)"
            :breadcrumbs="[
                ['label' => $resource->getIndexTitle(), 'url' => $manager->route('index'), 'icon' => $resource->getNavItem()?->icon()],
                $resource->getPageTitle($model)
            ]"
        >
            @if ($layout->hasForm('pagetitle'))
                <x-slot name="customTitle">
                    {{ $layout->findForm('pagetitle')->render() }}
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

    @foreach ($layout->filterByPosition('main')->exclude('pagetitle')->getComponents() as $component)
        {{ $component->render() }}
    @endforeach

    @if ($model instanceof \Thinktomorrow\Chief\Fragments\ContextOwner)
        <livewire:chief-fragments::contexts :resource-key="$resource::resourceKey()" :model="$model" />
    @endif

    @foreach ($layout->filterByPosition('main-bottom')->getComponents() as $component)
        {{ $component->render() }}
    @endforeach

    <x-slot name="sidebar">

        @foreach ($layout->filterByPosition('aside-top')->getComponents() as $component)
            {{ $component->displayAsTransparentForm()->render() }}
        @endforeach

        @if ($model instanceof \Thinktomorrow\Chief\Sites\BelongsToSites && $model instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable)
            <livewire:chief-wire::site-links :model="$model" />
        @elseif ($model instanceof \Thinktomorrow\Chief\Sites\BelongsToSites)
            <livewire:chief-wire::sites :model="$model" />
        @endif

        @foreach ($layout->filterByPosition('aside')->getComponents() as $component)
            {{ $component->displayAsTransparentForm()->render() }}
        @endforeach
    </x-slot>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.template>
