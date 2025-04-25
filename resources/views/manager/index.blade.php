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
        $breadcrumbs = [];
    } else {
        $breadcrumbs = [['label' => $resource->getIndexTitle(), 'url' => $manager->route('index'), 'icon' => $resource->getNavItem()?->icon()], 'Archief'];
    }
@endphp

<x-chief::page.template>
    <x-slot name="header">
        <x-chief::page.header :title="$title" :breadcrumbs="$breadcrumbs">
            <x-slot name="actions">
                @if ($resource->getIndexHeaderContent())
                    {!! $resource->getIndexHeaderContent() !!}
                @endif
            </x-slot>

            <div class="prose prose-dark prose-spacing max-w-2xl">
                {!! $resource->getIndexDescription() !!}
            </div>
        </x-chief::page.header>
    </x-slot>

    {{ $table->render() }}
</x-chief::page.template>
