<x-chief-form::window
    title="Fragmenten"
    :refresh-url="$manager->route('fragments-show', $owner)"
    tags="fragments"
    class="card"
>
    <div class="relative -mb-4">
        @include(
            'chief::manager.windows.fragments.component.fragment-select',
            [
                'ownerManager' => $manager,
                'inOpenState' => count($fragments) < 1,
            ]
        )

        <div
            data-sortable
            data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
            data-sortable-is-sorting
            data-fragments-container
            class="divide-y divide-grey-100 border-t border-grey-100"
        >
            @foreach ($fragments as $fragment)
                @include(
                    'chief-fragments::window-item',
                    [
                        'model' => $fragment['model'],
                        'manager' => $fragment['manager'],
                        'resource' => $fragment['resource'],
                        'owner' => $owner,
                        'ownerManager' => $manager,
                        'loop' => $loop,
                    ]
                )
            @endforeach
        </div>
    </div>
</x-chief-form::window>
