<x-chief::index :sidebar="false">
    <x-chief::table>

            @if($manager->filters()->anyRenderable())

                <x-slot name="search">
                    <form method="GET" class="space-y-6">

                        {!! $manager->filters()->render() !!}

                        <div>
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>

                    </form>
                </x-slot>

                <!-- hidden filters -->
                            <x-slot name="filters">
                                <a href="#" title="..." class="dropdown-link dropdown-link-success">Online</a>
                                <a href="#" title="..." class="dropdown-link dropdown-link-error">Offline</a>
                                <a href="#" title="..." class="dropdown-link dropdown-link-warning">Gearchiveerd</a>
                                <a href="#" title="..." class="dropdown-link dropdown-link-primary">Alle</a>
                            </x-slot>
            @endif

        @if(count($resource::getTableBulkActions()) > 0)
            <x-slot name="actions">
                @foreach($resource::getTableBulkActions() as $tableAction)
                    {!! $tableAction !!}
                @endforeach
            </x-slot>
        @endif

        <x-slot name="header">
            @if(count($resource::getTableBulkActions()) > 0)
                <x-chief::table.header>
                    <input
                        data-bulk-all-checkbox
                        type="checkbox"
                        name="bulk_all"
                        id="bulk_all"
                        class="with-custom-checkbox"
                    >
                </x-chief::table.header>
            @endif

            @foreach ($resource::getTableColumns() as $column)
                <x-chief::table.header :sortable="$column['sortable']" class="text-left display-base display-dark">
                    {{ $column['title'] }}
                </x-chief::table.header>
            @endforeach
        </x-slot>

        <x-slot name="body">
            @foreach ($models as $model)
                <x-chief::table.row>
                    @if(count($resource::getTableBulkActions()) > 0)
                        <x-chief::table.data>
                            <input
                                data-bulk-item-checkbox
                                type="checkbox"
                                name="item_{{ $loop->index }}"
                                id="item_{{ $loop->index }}"
                                class="with-custom-checkbox"
                            >
                        </x-chief::table.data>
                    @endif

                    @foreach($model->getTableRowHtml() as $rowHtml)
                        <x-chief::table.data class="leading-normal body-base body-dark">
                            {!! $rowHtml !!}
                        </x-chief::table.data>
                    @endforeach

                    <x-chief::table.data class="text-right">
                        @adminCan('edit')
                            <a href="@adminRoute('edit', $model)" title="Aanpassen">
                                <x-chief-icon-button icon="icon-edit"></x-chief-icon-button>
                            </a>
                        @endAdminCan
                    </x-chief::table.data>
                </x-chief::table.row>
            @endforeach
        </x-slot>
    </x-chief::table>

    @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
        {!! $models->links('chief::pagination.default') !!}
    @endif
</x-chief::index-table>
