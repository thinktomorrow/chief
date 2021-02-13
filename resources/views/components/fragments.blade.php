<div data-fragments-component class="space-y-10">
    <div class="flex justify-between items-center">
        <h2 class="mb-0">Fragments</h2>

        <div data-sidebar-component="fragmentcount">
            <span class="font-medium text-grey-500">Aantal: {{ count($fragments) }}</span>
        </div>
    </div>

    <div data-sidebar-component="fragments"
         data-sortable-fragments
         data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
         class="divide-y divide-grey-100 border-t border-b border-grey-100 -mx-12"
    >
        @foreach($fragments as $fragment)
            @include('chief::managers.fragments._card', [
                'model' => $fragment['model'],
                'owner' => $owner,
                'manager' => $fragment['manager'],
                'loop' => $loop,
            ])
        @endforeach
    </div>

    @include('chief::managers.fragments._add', [
        'allowedFragments' => $allowedFragments
    ])
</div>
