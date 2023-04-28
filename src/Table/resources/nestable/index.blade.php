@php
    $title = ucfirst($resource->getIndexTitle());
    $models = collect($resource->getModels());

    $is_archive_index = $is_archive_index ?? false;

    $tableActions = $resource->getTableActions($manager);
    if (! is_array($tableActions)) {
        $tableActions = iterator_to_array($tableActions);
    }
    $tableActionsCount = count($tableActions);

    $tableHeaders = count($models) > 0 ? $resource->getTableHeaders($manager, $models->first()) : [];

    $showOptionsColumn = $manager->can('edit') || $manager->can('preview') || $manager->can('duplicate') || $manager->can('state-update')
@endphp

<x-chief::page.template :title="$title">
    <x-slot name="hero">
        <x-chief::page.hero :title="$title" :breadcrumbs="[$resource->getIndexBreadCrumb()]">
            @if($resource->getIndexHeaderContent())
                {!! $resource->getIndexHeaderContent() !!}
            @endif

            @adminCan('create')
                <a href="@adminRoute('create')" title="{{ ucfirst($resource->getLabel()) }} toevoegen" class="btn btn-primary">
                    {{ ucfirst($resource->getLabel()) }} toevoegen
                </a>
            @endAdminCan
        </x-chief::page.hero>
    </x-slot>

    <x-chief::page.grid>
        <div>
            {{-- <div class="container mb-8">
                <div class="row-start-start gutter-2">
                    <div class="w-full lg:w-1/3">
                        <div class="p-4 text-center bg-grey-50 bg-gradient-to-br from-grey-50 to-grey-100 rounded-xl">
                            <span class="font-medium h6 h6-dark">
                                <span class="font-semibold text-primary">24</span>
                                pagina's online
                            </span>
                        </div>
                    </div>

                    <div class="w-full lg:w-1/3">
                        <div class="p-4 text-center bg-grey-50 bg-gradient-to-br from-grey-50 to-grey-100 rounded-xl">
                            <span class="font-medium h6 h6-dark">
                                <span class="font-semibold text-primary">7</span>
                                pagina's offline
                            </span>
                        </div>
                    </div>

                    <div class="w-full lg:w-1/3">
                        <div class="p-4 text-center bg-grey-50 bg-gradient-to-br from-grey-50 to-grey-100 rounded-xl">
                            <span class="font-medium h6 h6-dark">
                                Laatste aanpassing
                                <span class="font-semibold text-primary">3u geleden</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="mb-4">
                <div class="container max-w-1920">
                    <div class="flex items-start justify-between gap-4 form-light">
                        <div class="flex items-start gap-4">
                            <div class="mt-0.5 -mx-2">
                                @adminCan('sort-index', $model)
                                    <a href="{{ $manager->route('index-for-sorting') }}" title="Sorteer handmatig">
                                        <x-chief::icon-button color="grey">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd" d="M6.97 2.47a.75.75 0 011.06 0l4.5 4.5a.75.75 0 01-1.06 1.06L8.25 4.81V16.5a.75.75 0 01-1.5 0V4.81L3.53 8.03a.75.75 0 01-1.06-1.06l4.5-4.5zm9.53 4.28a.75.75 0 01.75.75v11.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06l3.22 3.22V7.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                                            </svg>
                                        </x-chief::icon-button>
                                    </a>
                                @endAdminCan
                            </div>

                            <x-chief::input.search id="search" class="w-64" placeholder="Zoek naar een pagina ..."/>

                            <span class="inline-flex text-sm leading-5 rounded-md shadow-sm isolate">
                                <button type="button" class="relative inline-flex items-center px-3 py-2 bg-white text-grey-900 rounded-l-md ring-1 ring-inset ring-grey-200 hover:bg-grey-50 focus:z-10 focus:ring-grey-200 focus:bg-grey-100">
                                    Alle
                                </button>
                                <button type="button" class="relative inline-flex items-center px-3 py-2 -ml-px bg-white text-grey-900 ring-1 ring-inset ring-grey-200 hover:bg-grey-50 focus:z-10 focus:ring-grey-200 focus:bg-grey-100">
                                    Online
                                </button>
                                <button type="button" class="relative inline-flex items-center px-3 py-2 -ml-px bg-white text-grey-900 ring-1 ring-inset ring-grey-200 hover:bg-grey-50 focus:z-10 focus:ring-grey-200 focus:bg-grey-100">
                                    Offline
                                </button>
                                <button type="button" class="relative inline-flex items-center px-3 py-2 -ml-px bg-white text-grey-900 rounded-r-md ring-1 ring-inset ring-grey-200 hover:bg-grey-50 focus:z-10 focus:ring-grey-200 focus:bg-grey-100">
                                    Gearchiveerd
                                </button>
                            </span>
                        </div>

                        <div class="flex items-start gap-2 -mx-2 mt-0.5">
                            @foreach ($tableActions as $bulkAction)
                                {{ $bulkAction->render() }}
                            @endforeach

                            <button data-toggle-class="#page-sidebar" type="button">
                                <x-chief::icon-button icon="icon-ellipsis-vertical" color="grey"/>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <x-chief::table
                :filters="(!$resource->showIndexSidebarAside() ? $manager->filters()->all() : [])"
                :sticky="$resource->displayTableHeaderAsSticky()"
            >
                @if ($tableActionsCount > 0)
                    <x-slot name="actions">
                        @foreach ($tableActions as $bulkAction)
                            {{ $bulkAction->render() }}
                        @endforeach
                    </x-slot>
                @endif

                <x-slot name="header">
                    @if ($tableActionsCount > 0)
                        <x-chief::table.header class="form-light">
                            <x-chief::input.checkbox data-bulk-all-checkbox name="bulk_all" id="bulk_all" class="mt-1"/>
                        </x-chief::table.header>
                    @endif

                    @foreach ($tableHeaders as $tableHead)
                        {{ $tableHead->render() }}
                    @endforeach

                    @if ($showOptionsColumn)
                        <x-chief::table.header/>
                    @endif
                </x-slot>

                <x-slot name="body">
                    @forelse ($tree as $node)
                        @include('chief-table::nestable.table-node', ['node' => $node, 'level' => 0])
                    @empty
                        <x-chief::table.row>
                            <x-chief::table.data colspan="100%" class="text-center">
                                Geen {{ $resource->getIndexTitle() }} gevonden
                            </x-chief::table.data>
                        </x-chief::table.row>
                    @endforelse
                </x-slot>
            </x-chief::table>

            @if ($models instanceof \Illuminate\Contracts\Pagination\Paginator)
                {!! $models->links('chief::pagination.default') !!}
            @endif
        </div>
    </x-chief::page.grid>

    @if ($resource->showIndexSidebarAside())
        <x-slot name="sidebar">
            @include('chief::templates.page.index.default-sidebar')
        </x-slot>
    @else
        @include('chief::templates.page.index.inline-sidebar')
    @endif
</x-chief::page.template>
