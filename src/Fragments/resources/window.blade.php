<x-chief-form::window
    :refresh-url="$manager->route('fragments-show', $owner)"
    tags="fragments"
    class="card"
>
    <div class="relative -my-6">
        @include('chief::manager.windows.fragments.component.fragment-select', [
            'ownerManager' => $manager,
            'inOpenState' => count($fragments) < 1
        ])

        <div
            data-fragments-container
            data-sortable-fragments
            data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
            class="divide-y divide-grey-100"
        >
            @foreach($fragments as $fragment)
                @include('chief-fragments::window-item', [
                    'model' => $fragment['model'],
                    'manager' => $fragment['manager'],
                    'owner' => $owner,
                    'ownerManager' => $manager,
                    'loop' => $loop,
                ])
            @endforeach
        </div>
    </div>
</x-chief-form::window>
