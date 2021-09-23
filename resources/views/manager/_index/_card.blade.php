<div class="px-6 py-4" data-sortable-id="{{ $model->id }}">
    <div class="space-y-1">
        <div class="flex justify-between">
            @adminCan('edit')
                <a href="@adminRoute('edit', $model)" class="flex items-center space-x-2">
            @endAdminCan
                    <span class="text-lg font-semibold text-grey-900">
                        @adminConfig('rowTitle')
                    </span>

                    @if(\Thinktomorrow\Chief\Admin\Settings\Homepage::is($model))
                        <span class="text-sm label label-info label-xs">
                            Homepage
                        </span>
                    @endif
            @adminCan('edit')
                </a>
            @endAdminCan

            <div>
                @include('chief::manager._index._options')
            </div>
        </div>

        @adminConfig('rowContent')
    </div>
</div>
