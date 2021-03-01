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
        class="relative divide-y divide-grey-150 border-t border-b border-grey-150 -mx-12"
    >
        @foreach($fragments as $fragment)
            @include('chief::fragments.component._card', [
                'model' => $fragment['model'],
                'owner' => $owner,
                'manager' => $fragment['manager'],
                'loop' => $loop,
            ])
        @endforeach
    </div>

    @include('chief::fragments.component.fragment-new')

</div>
