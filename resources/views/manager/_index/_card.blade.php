<div data-sortable-handle data-sortable-id="{{ $model->getKey() }}" class="py-4">
    <div class="space-y-2">
        <div class="flex justify-between">
            @adminCan('edit')
                <a href="@adminRoute('edit', $model)" class="w-full mt-0.5 space-x-1">
            @endAdminCan
                    <span class="text-lg display-dark display-base">
                        {!! $resource->getIndexCardTitle($model) !!}
                    </span>

                    @if(\Thinktomorrow\Chief\Admin\Settings\Homepage::is($model))
                        <span class="align-bottom with-xs-labels">
                            <span class="label label-info"> Homepage </span>
                        </span>
                    @endif
            @adminCan('edit')
                </a>
            @endAdminCan

            <div class="shrink-0">
                @include('chief::manager._index._options')
            </div>
        </div>

        {!! $resource->getIndexCardContent($model) !!}
    </div>
</div>
