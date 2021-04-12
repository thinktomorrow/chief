<div data-fragments-component class="space-y-4">
    <div class="flex justify-between items-center">
        <h3 class="mb-0">Fragments</h3>

        <div>
            <span class="font-medium text-grey-500">Aantal: {{ count($fragments) }}</span>
        </div>
    </div>

    <div
        data-fragments-container
        data-sortable-fragments
        data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
        class="relative divide-y divide-grey-200 -mx-12"
    >
        @foreach($fragments as $fragment)
            @include('chief::manager.cards.fragments.component._card', [
                'model' => $fragment['model'],
                'owner' => $owner,
                'manager' => $fragment['manager'],
                'loop' => $loop,
            ])
        @endforeach
    </div>

    @include('chief::manager.cards.fragments.component.fragment-new')

</div>
