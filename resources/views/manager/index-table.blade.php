<x-chief::index :sidebar="false">
    <x-chief::table :filters="$manager->filters()->all()">
        @if(count($resource::getTableBulkActions()) > 0)
            <x-slot name="actions">
                @foreach($resource::getTableBulkActions() as $tableAction)
                    {!! $tableAction !!}
                @endforeach
            </x-slot>
        @endif

        <x-slot name="header">
           {{-- @if(count($resource::getTableBulkActions()) > 0)
               <x-chief::table.header>
                   <input
                       data-bulk-all-checkbox
                       type="checkbox"
                       name="bulk_all"
                       id="bulk_all"
                       class="with-custom-checkbox"
                   >
               </x-chief::table.header>
            @endif --}}

            @foreach ($resource::getTableColumns() as $column)
                <x-chief::table.header :sortable="$column['sortable']">
                    {{ $column['title'] }}
                </x-chief::table.header>
            @endforeach
        </x-slot>

        <x-slot name="body">
            @forelse ($models as $model)
                <x-chief::table.row>
                   {{-- @if(count($resource::getTableBulkActions()) > 0)
                       <x-chief::table.data>
                           <input
                               data-bulk-item-checkbox
                               type="checkbox"
                               name="item_{{ $loop->index }}"
                               id="item_{{ $loop->index }}"
                               class="with-custom-checkbox"
                           >
                       </x-chief::table.data>
                   @endif --}}

                    @foreach($model->getTableRowHtml() as $rowHtml)
                        <x-chief::table.data>
                            {!! $rowHtml !!}
                        </x-chief::table.data>
                    @endforeach

                    @adminCan('edit')
                        <x-chief::table.data class="text-right">
                            <a href="@adminRoute('edit', $model)" title="Aanpassen">
                                <x-chief-icon-button icon="icon-edit"></x-chief-icon-button>
                            </a>
                        </x-chief::table.data>
                    @endAdminCan
                </x-chief::table.row>
            @empty
                <x-chief::table.row>
                    <x-chief::table.data colspan="100%" class="text-center">
                        Geen {{ $resource->getIndexTitle() }} gevonden
                    </x-chief::table.data>
                </x-chief::table.row>
            @endforelse
        </x-slot>
    </x-chief::table>

    @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
        {!! $models->links('chief::pagination.default') !!}
    @endif

    <div class="row-start-start gutter-3">
        <div class="w-full sm:w-1/2 md:w-1/3">
            @if($resource->getIndexSidebar())
                {!! $resource->getIndexSidebar() !!}
            @endif
        </div>

        <div class="w-full sm:w-1/2 md:w-1/3">
            @include('chief::manager._index.sort_card')
        </div>

        <div class="w-full sm:w-1/2 md:w-1/3">
            @include('chief::manager._index.archive_card')
        </div>
    </div>
</x-chief::index-table>
