<div class="window-spacing" data-sortable-id="{{ $model->id }}">
    <div class="space-y-2">
        <div class="flex justify-between">
            @adminCan('edit')
                <a href="@adminRoute('edit', $model)" class="flex items-center space-x-2">
            @endAdminCan
                    <span class="text-lg window-heading">
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
