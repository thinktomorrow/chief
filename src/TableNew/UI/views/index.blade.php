@php
    $rows = json_decode(
        json_encode([
            ['id' => 5, 'title' => 'new kid on the block'],
            ['id' => 6, 'title' => 'new kid on the block'],
            [
                'id' => 8,
                'title' => 'new kid on the block',
                'rows' => json_decode(
                    json_encode([
                        ['id' => 45, 'title' => 'new kid on the block'],
                        ['id' => 77, 'title' => 'new kid on the blodfqdf'],
                    ]),
                ),
            ],
        ]),
    );

    $listingComponent = \Thinktomorrow\Chief\TableNew\UI\Livewire\ArticleListing::class;

    $level = 0;
@endphp

<x-chief::page.template>
    <div class="container my">
        <livewire:is :component="$listingComponent" />
    </div>
</x-chief::page.template>
