<div class="w-full p-6" data-sortable-id="{{ $model->id }}">
    <div class="space-y-2">
        <div class="flex justify-between">
            @adminCan('edit')
                <a href="@adminRoute('edit', $model)" class="flex items-center space-x-2">
            @endAdminCan
                    <span class="text-lg font-medium text-grey-900">
                        @adminConfig('rowTitle')
                    </span>

                    @if(\Thinktomorrow\Chief\Admin\Settings\Homepage::is($model))
                        <span class="text-sm label label-info">
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
