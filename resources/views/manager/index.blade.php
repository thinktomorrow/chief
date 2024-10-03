@php
    use Thinktomorrow\Chief\Table\Table\References\TableReference;

    $is_archive_index = $is_archive_index ?? false;
    $title = ucfirst($resource->getIndexTitle());

    if ($is_archive_index) {
        $title .= ' archief ';
        $table = $resource->getArchivedIndexTable();
        $table->setTableReference(new TableReference($resource::class, 'getArchivedIndexTable'));
    } else {
        $table = $resource->getIndexTable();
        $table->setTableReference(new TableReference($resource::class, 'getIndexTable'));
    }
@endphp

<x-chief::page.template>
    <x-slot name="hero">
        <x-chief::page.hero
            :title="$title"
            :breadcrumbs="!$is_archive_index ? [$resource->getPageBreadCrumb()] : [
                new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar overzicht', $manager->route('index'))
            ]"
        >
            @if ($resource->getIndexDescription())
                <x-slot name="description">
                    {{ $resource->getIndexDescription() }}
                </x-slot>
            @endif

            @if ($resource->getIndexHeaderContent())
                {!! $resource->getIndexHeaderContent() !!}
            @endif
        </x-chief::page.hero>
    </x-slot>

    <div class="container">
        {{ $table->render() }}
    </div>
</x-chief::page.template>
