<div data-fragments-component class="space-y-4">
    <div>
        <span class="text-xl font-semibold text-grey-900">Fragmenten</span>
    </div>

    <div class="relative -m-12 border-t border-b divide-y divide-grey-100 border-grey-100">

        @include('chief::manager.cards.fragments.component.fragment-select', [
            'ownerManager' => $manager,
            'inOpenState' => count($fragments) < 1
        ])

        <div data-fragments-container
             data-sidebar-component="fragments"
             data-sortable-fragments
             data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
             class="divide-y divide-grey-100"
        >
            @foreach($fragments as $fragment)
                @include('chief::manager.cards.fragments.component._card', [
                    'model' => $fragment['model'],
                    'manager' => $fragment['manager'],
                    'owner' => $owner,
                    'ownerManager' => $manager,
                    'loop' => $loop,
                    'isNested' => true
                ])
            @endforeach
        </div>

    </div>
</div>
