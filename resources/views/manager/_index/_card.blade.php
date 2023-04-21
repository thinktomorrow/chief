<div data-sortable-handle data-sortable-id="{{ $model->getKey() }}" class="py-4 space-y-2">
    <div class="flex items-start justify-between gap-4 group">
        <div class="flex flex-wrap gap-1 mt-[0.2rem]">
            @adminCan('edit')
                <a
                    href="{{ $manager->route('edit', $model) }}"
                    title="{{ $resource->getPageTitle($model) }}"
                    class="mr-1 font-medium body-dark group-hover:underline"
                >
                    {!! $resource->getIndexCardTitle($model) !!}
                </a>
            @elseAdminCan
                <span class="mr-1 font-medium body-dark">
                    {!! $resource->getIndexCardTitle($model) !!}
                </span>
            @endAdminCan

            @if (\Thinktomorrow\Chief\Admin\Settings\Homepage::is($model))
                <span class="label label-xs label-primary mt-[1px]">Home</span>
            @endif

            @if ($model instanceof Thinktomorrow\Chief\Plugins\Tags\Application\Taggable\Taggable)
                <x-chief-tags::tags :tags="$model->getTags()" size="xs" threshold="4"/>
            @endif
        </div>

        <div class="shrink-0">
            @include('chief::manager._index._options')
        </div>
    </div>

    {!! $resource->getIndexCardContent($model) !!}
</div>
