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

            @include('chief::manager._partials.edit-actions')
        </x-chief::page.hero>
    </x-slot>

    <div class="space-y-6">
        <x-chief-form::forms position="main" />

        @if ($model instanceof \Thinktomorrow\Chief\Fragments\ContextOwner)
            <livewire:chief-fragments::contexts :resource-key="$resource::resourceKey()" :model="$model" />
        @endif

        <x-chief-form::forms position="main-bottom" />
    </div>

    <x-slot name="sidebar">
        <div class="space-y-8">

            @if($model instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract && chiefAdmin()->can('update-page'))
                @foreach($model->getStateKeys() as $stateKey)
                    <livewire:chief-wire::state :model="$model" :state-key="$stateKey" />
                @endforeach
            @endif

            @if($model instanceof \Thinktomorrow\Chief\Sites\BelongsToSites)
                <livewire:chief-wire::site-tabs :model="$model" />
                <livewire:chief-wire::site-links :model="$model" />
            @endif

            <x-chief-form::forms position="aside-top" />

            {{-- <x-chief::window.states /> --}}
            {{-- <x-chief::window.links /> --}}
            <x-chief-form::forms position="aside" />
        </div>
    </x-slot>

    @include('chief::templates.page._partials.editor-script')
</x-chief::page.multisite-template>
