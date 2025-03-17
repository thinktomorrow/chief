@php
    use Thinktomorrow\Chief\Sites\BelongsToSites;

@endphp
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

            @if($model instanceof BelongsToSites)
                <livewire:chief-wire::site-tabs :resource-key="$resource::resourceKey()" :model="$model" />
            @endif

            @include('chief::manager._partials.edit-actions')
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid>
        <x-chief-form::forms position="main" />

        @if($model instanceof \Thinktomorrow\Chief\Fragments\ContextOwner)
            <livewire:chief-fragments::contexts :resource-key="$resource::resourceKey()" :model="$model" />
        @endif

        <x-chief-form::forms position="main-bottom" />

        <x-slot name="aside">
            <x-chief-form::forms position="aside-top" />

            @if($model instanceof BelongsToSites)
                <livewire:chief-wire::site-links :resource-key="$resource::resourceKey()" :model="$model" />
            @endif
            {{--            <x-chief::window.states />--}}
            {{--            <x-chief::window.links />--}}
            <x-chief-form::forms position="aside" />
        </x-slot>
    </x-chief::page.grid>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.template>
