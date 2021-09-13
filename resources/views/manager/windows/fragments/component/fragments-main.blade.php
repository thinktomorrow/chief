<div
    data-fragments-component
    class="{{ isset($class) ? $class : '' }}"
>
    <div class="relative -window-md divide-y divide-grey-100">
        @include('chief::manager.windows.fragments.component.fragment-select', [
            'ownerManager' => $manager,
            'inOpenState' => count($fragments) < 1
        ])

        <div data-fragments-container
             data-sortable-fragments
             data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
             class="divide-y divide-grey-100">
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
</div>
