<div data-fragments-component class="space-y-4">
    <div>
        <span class="text-xl font-semibold text-grey-900">Nested fragments</span>
    </div>

    <div
        data-fragments-container
        data-sidebar-component="fragments"
        data-sortable-fragments
        data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
        class="relative divide-y divide-grey-100 border-t border-b border-grey-100 -m-12"
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
</div>
