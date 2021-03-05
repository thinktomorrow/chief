<div data-fragments-component class="space-y-4">
    <div class="flex justify-between items-center">
        <h3 class="mb-0">Fragments</h3>

        <div data-sidebar-component="fragmentcount">
            <span class="font-medium text-grey-500">Aantal: {{ count($fragments) }}</span>
        </div>
    </div>

    <div
         data-fragments-container
         data-sidebar-component="fragments"
         data-sortable-fragments
         data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
         class="divide-y divide-grey-150 border-t border-b border-grey-150 -mx-12"
    >
        @foreach($fragments as $fragment)
            @include('chief::managers.fragments.component._card', [
                'model' => $fragment['model'],
                'owner' => $owner,
                'manager' => $fragment['manager'],
                'loop' => $loop,
            ])
        @endforeach
    </div>

</div>
