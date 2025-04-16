@php
    $hasAnyAsideTopComponents = count($layout->filterByPosition('aside-top')->getComponents()) > 0;
    $hasSiteLinks = $model instanceof \Thinktomorrow\Chief\Sites\HasAllowedSites && $model instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable;
    $hasSites = $model instanceof \Thinktomorrow\Chief\Sites\HasAllowedSites;
    $hasStates = $model instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract && chiefAdmin()->can('update-page') && count($model->getStateKeys()) > 0;
    $hasAnyAsideComponents = count($layout->filterByPosition('aside')->getComponents()) > 0;

    $showSidebar = $hasAnyAsideTopComponents || $hasSiteLinks || $hasSites || $hasStates || $hasAnyAsideComponents;
@endphp

<x-chief::page.template :title="$resource->getPageTitle($model)" :container="$showSidebar ? '2xl' : 'lg'">
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
                @if ($model instanceof \Thinktomorrow\Chief\Sites\HasAllowedSites)
                    <livewire:chief-wire::model-site-toggle :model="$model" />
                @endif

                @if ($hasStates)
                    @foreach ($model->getStateKeys() as $stateKey)
                        <livewire:chief-wire::state :model="$model" :state-key="$stateKey" />
                    @endforeach
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

    @if ($showSidebar)
        <x-slot name="sidebar">
            @foreach ($layout->filterByPosition('aside-top')->getComponents() as $component)
                {{ $component->displayAsTransparentForm()->render() }}
            @endforeach

            @if ($hasSiteLinks)
                <livewire:chief-wire::site-links :model="$model" />
            @elseif ($hasSites)
                <livewire:chief-wire::site-selection :model="$model" />
            @endif

            @foreach ($layout->filterByPosition('aside')->getComponents() as $component)
                {{ $component->displayAsTransparentForm()->render() }}
            @endforeach
        </x-slot>
    @endif

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.template>
