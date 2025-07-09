@php
    $hasAnyAsideTopComponents = count($layout->filterByPosition('aside-top')->getComponents()) > 0;
    $hasContexts = $model instanceof \Thinktomorrow\Chief\Fragments\ContextOwner;
    $hasSites = $model instanceof \Thinktomorrow\Chief\Sites\HasAllowedSites;
    $hasLinks = $model instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable;
    $hasStates = $model instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract && chiefAdmin()->can('update-page') && count($model->getStateKeys()) > 0;
    $hasAnyAsideComponents = count($layout->filterByPosition('aside')->getComponents()) > 0;

    $showSidebar = $hasAnyAsideTopComponents || $hasLinks || $hasAnyAsideComponents;
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

            <div class="flex justify-between">
                <livewire:chief-wire::site-selection :model="$model" />

                @if ($hasSites && $hasStates)
                    @foreach ($model->getStateKeys() as $stateKey)
                        <livewire:chief-wire::state :model="$model" :state-key="$stateKey" />
                    @endforeach
                @endif
            </div>

            @if (!$hasSites && $hasStates)
                <x-slot name="actions">
                    @if ($hasStates)
                        @foreach ($model->getStateKeys() as $stateKey)
                            <livewire:chief-wire::state :model="$model" :state-key="$stateKey" />
                        @endforeach
                    @endif
                </x-slot>
            @endif
        </x-chief::page.header>
    </x-slot>

    @foreach ($layout->filterByPosition('main')->exclude('pagetitle')->getComponents() as $component)
        {{ $component->render() }}
    @endforeach

    @if ($hasContexts)
        <livewire:chief-fragments::contexts
            :resource-key="$resource::resourceKey()"
            :model="$model"
            :active-context-id="request()->input('context')"
        />
    @endif

    @foreach ($layout->filterByPosition('main-bottom')->getComponents() as $component)
        {{ $component->render() }}
    @endforeach

    @if ($showSidebar)
        <x-slot name="sidebar">
            @foreach ($layout->filterByPosition('aside-top')->getComponents() as $component)
                {{ $component->render() }}
            @endforeach

            @if ($hasLinks)
                <livewire:chief-wire::links :model="$model" />
            @endif

            @foreach ($layout->filterByPosition('aside')->getComponents() as $component)
                {{ $component->render() }}
            @endforeach
        </x-slot>
    @endif
</x-chief::page.template>
