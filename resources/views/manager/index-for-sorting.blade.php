<x-chief::page.template title="Herschikken">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
                ['label' => $resource->getIndexTitle($model), 'url' => $manager->route('index', $model), 'icon' => $resource->getNavItem()?->icon()],
                'Herschikken'
            ]"
        />
    </x-slot>

    <x-chief::window class="card">
        <div
            data-sortable
            data-sortable-is-sorting
            data-sortable-endpoint="{{ $manager->route('sort-index') }}"
            data-sortable-id-type="{{ $resource->getSortableType() }}"
            class="row-start-stretch gutter-1"
        >
            @foreach ($models as $model)
                <div
                    data-sortable-handle
                    data-sortable-id="{{ $model->getKey() }}"
                    class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4"
                >
                    <div
                        class="transition-75 h-full cursor-move rounded-md border border-grey-100 bg-grey-50 p-3 hover:bg-grey-100"
                    >
                        <p class="h6 h1-dark text-sm">
                            {{ $resource->getPageTitle($model) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </x-chief::window>

    @if ($models instanceof \Illuminate\Contracts\Pagination\Paginator)
        {!! $models->links('chief::pagination.default') !!}
    @endif

    <x-slot name="sidebar">
        <x-chief::window class="card">
            <div class="space-y-4">
                <x-chief::button href="{{ $manager->route('index') }}" variant="blue">
                    Stop met herschikken
                </x-chief::button>

                <p class="body-dark body text-sm">
                    Sleep de blokken in de gewenste volgorde. De volgorde wordt automatisch bewaard.
                </p>
            </div>
        </x-chief::window>
    </x-slot>
</x-chief::page.template>
