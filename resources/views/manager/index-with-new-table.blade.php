@php
    use Thinktomorrow\Chief\TableNew\Table\TableReference;

    $table = $resource->getIndexTable();
    $table->setTableReference(new TableReference($resource::class, 'indexTable'));

    $table2 = $resource->getOtherIndexTable();
    $table2->setTableReference(new TableReference($resource::class, 'otherIndexTable'));
@endphp

<x-chief::page.template>
    <div class="container my">
        {{ $table->render() }}
    </div>

    <div class="container my">
        {{ $table2->render() }}
    </div>
</x-chief::page.template>
