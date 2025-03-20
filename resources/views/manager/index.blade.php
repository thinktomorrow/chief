@php
    use Thinktomorrow\Chief\Table\Table\References\TableReference;

    $is_archive_index = $is_archive_index ?? false;
    $is_reorder_index = $is_reorder_index ?? false;
    $title = ucfirst($resource->getIndexTitle());

    if ($is_archive_index) {
        $title .= ' archief ';
    } elseif ($is_reorder_index) {
        $title .= ' herschikken ';
    }

    if (! $is_archive_index && ! $is_reorder_index) {
        $breadcrumbs = [['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'], $resource->getIndexTitle($model)];
    } else {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'icon' => 'home'],
            ['label' => $resource->getIndexTitle($model), 'url' => $manager->route('index', $model), 'icon' => $resource->getNavItem()?->icon()],
            'Archief',
        ];
    }
@endphp

<x-chief::page.multisite-template>
    <x-slot name="header">
        <x-chief::page.header :title="$title" :breadcrumbs="$breadcrumbs">
            <x-slot name="actions">
                @if ($resource->getIndexHeaderContent())
                    {!! $resource->getIndexHeaderContent() !!}
                @endif
            </x-slot>

            {!! $resource->getIndexDescription() !!}
        </x-chief::page.header>
    </x-slot>

    {{ $table->render() }}
</x-chief::page.multisite-template>
