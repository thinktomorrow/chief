@php
    use Thinktomorrow\Chief\TableNew\Table\TableReference;

    $table = $resource->getIndexTable();
    $table->setTableReference(new TableReference($resource::class, 'indexTable'));
@endphp

<x-chief::page.template>
    <div class="container my">
        {{ $table->render() }}
    </div>
</x-chief::page.template>
