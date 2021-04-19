<div class="w-full px-12 py-6" data-sortable-id="{{ $model->id }}">
    <div class="relative">
        <div class="absolute top-0 right-0">
            @include('chief::manager._index._options')

            @adminConfig('rowBadge')
        </div>

        @adminCan('edit')
            <a href="@adminRoute('edit', $model)" class="flex items-center space-x-2">
        @endAdminCan
                <span class="text-lg font-semibold text-grey-900">@adminConfig('rowTitle')</span>

                @if(\Thinktomorrow\Chief\Admin\Settings\Homepage::is($model))
                    <span class="label label-secondary text-sm">
                        Homepage
                    </span>
                @endif
        @adminCan('edit')
            </a>
        @endAdminCan

        <div>
            @adminConfig('rowContent')
        </div>
    </div>
</div>

{{-- <div class="s-column-6 m-column-6 inset-xs flex" data-sortable-id="{{ $model->id }}">
    <div class="row bg-white border border-grey-100 rounded inset-s relative" style="flex:1 1 0%;">
        <div class="column flex flex-col justify-between">
            @adminCan('edit')
                <a data-context-sidebar href="@adminRoute('edit', $model)" class="flex items-center">
                    <h3 class="mb-0">@adminConfig('rowTitle')</h3>
                    @if(\Thinktomorrow\Chief\Admin\Settings\Homepage::is($model))
                        <span class="label label-tertiary flex items-center ml-2">
                            <svg width="14" height="14" class="fill-current"><use xlink:href="#home"/></svg>
                            <span class="ml-2 text-sm">homepage</span>
                        </span>
                    @endif
                </a>
            @elseAdminCan
                <span class="text-black font-bold">@adminConfig('rowTitle')</span>
            @endAdminCan
            <div>
                @adminConfig('rowContent')
            </div>
        </div>
        <div class="column-1 text-right flex flex-col justify-between items-end">
            @include('chief::back.managers._index._options')
            @adminConfig('rowBadge')
        </div>
    </div>
</div> --}}
