<div data-fragments-component class="space-y-4">
    <div>
        <span class="text-lg display-base display-dark">Fragmenten</span>
    </div>

    <div class="relative -my-6 border-t-2 border-b-2 border-dashed border-primary-50">
        <div data-sidebar-component="fragments-select-nested">
            @include('chief::manager.windows.fragments.component.fragment-select', [
                'ownerManager' => $manager,
                'inOpenState' => count($fragments) < 1
            ])
        </div>

        <div
            data-fragments-container
            data-sidebar-component="fragments"
            data-sortable
            data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
            class="divide-y divide-grey-100"
        >
            @foreach($fragments as $fragment)
                @include('chief::manager.windows.fragments.component._card', [
                    'model' => $fragment['model'],
                    'manager' => $fragment['manager'],
                    'resource' => $fragment['resource'],
                    'owner' => $owner,
                    'ownerManager' => $manager,
                    'loop' => $loop,
                    'isNested' => true
                ])
            @endforeach
        </div>

    </div>
</div>
