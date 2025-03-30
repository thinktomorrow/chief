<x-chief::page.template :title="$resource->getPageTitle($model)">
    <x-slot name="header">
        <x-chief::page.header
            :title="$resource->getPageTitle($model)"
            :breadcrumbs="[
                ['label' => $resource->getIndexTitle(), 'url' => $manager->route('index'), 'icon' => $resource->getNavItem()?->icon()],
                $resource->getPageTitle($model)
            ]"
        >
            {{--            @if ($layout->has('pagetitle'))--}}
            <x-slot name="customTitle">
                {{--                    <x-chief-form::forms id="pagetitle" />--}}
            </x-slot>
            {{--            @endif--}}

            <x-slot name="actions">
                @if ($model instanceof \Thinktomorrow\Chief\Sites\BelongsToSites)
                    <livewire:chief-wire::site-tabs :model="$model" />
                @endif

                @include('chief::manager._partials.edit-actions')
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    @foreach ($layout->filterByPosition('main')->getComponents() as $component)
        {{ $component->render() }}
    @endforeach
    {{--    <x-chief-form::forms position="main" />--}}

    @if ($model instanceof \Thinktomorrow\Chief\Fragments\ContextOwner)
        <livewire:chief-fragments::contexts :resource-key="$resource::resourceKey()" :model="$model" />
    @endif

    @foreach ($layout->filterByPosition('main-bottom')->getComponents() as $component)
        {{ $component->render() }}
    @endforeach

    {{--    <x-chief-form::forms position="main-bottom" />--}}

    <x-slot name="sidebar">

        @foreach ($layout->filterByPosition('aside-top')->getComponents() as $component)
            {{ $component->render() }}
        @endforeach

        {{--        <x-chief-form::forms position="aside-top" />--}}

        @if ($model instanceof \Thinktomorrow\Chief\Sites\BelongsToSites && $model instanceof \Thinktomorrow\Chief\Site\Visitable\Visitable)
            <livewire:chief-wire::site-links :model="$model" />
        @elseif ($model instanceof \Thinktomorrow\Chief\Sites\BelongsToSites)
            <livewire:chief-wire::sites :model="$model" />
        @endif

        @foreach ($layout->filterByPosition('aside')->getComponents() as $component)
            {{ $component->render() }}
        @endforeach
        {{--        <x-chief-form::forms position="aside" />--}}
    </x-slot>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.template>
