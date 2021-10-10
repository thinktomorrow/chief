<x-chief-card
        class="{{ isset($class) ? $class : '' }}"
        title="{{ $title ?? null }}"
        :editRequestUrl="$manager->route('fields-edit', $model, $tagged)"
        sidebarTrigger="data-sidebar-trigger={{ $tagged }}"
>
    <div class="row-start-start gutter-3">
        <div class="space-y-2">
            {!! $slot !!}
        </div>
    </div>
</x-chief-card>
