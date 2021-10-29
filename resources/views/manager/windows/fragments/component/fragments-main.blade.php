<div data-fragments-component>
    <x-chief::window>
        <div class="relative -my-6 border-b divide-y divide-grey-100 border-grey-100">
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
                    @include('chief::manager.windows.fragments.component._card', [
                        'model' => $fragment['model'],
                        'manager' => $fragment['manager'],
                        'owner' => $owner,
                        'ownerManager' => $manager,
                        'loop' => $loop,
                    ])
                @endforeach
            </div>
        </div>
    </x-chief:window>
</div>
