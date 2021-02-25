<div data-fragments-component class="space-y-4">
    <div class="flex justify-between items-center">
        <h3 class="mb-0">Fragments</h3>

        <div data-sidebar-component="fragmentcount">
            <span class="font-medium text-grey-500">Aantal: {{ count($fragments) }}</span>
        </div>
    </div>

    <div data-fragments-component-inner
         data-sidebar-component="fragments"
         data-sortable-fragments
         data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
         class="divide-y divide-grey-150 border-t border-b border-grey-150 -mx-12"
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
</div>

<div hidden id="js-fragment-selection-template">
    <div
        data-sortable-handle
        data-fragments-new-selection
        class="w-full px-12 py-12 space-y-4 pop"
    >
        <div>
            <p class="font-medium text-grey-700 text-center">
                Kies een blok om toe te voegen
            </p>
        </div>

        <div class="flex justify-center items-center space-x-2">
            @forelse($allowedFragments as $allowedFragment)
                <a
                    data-sidebar-fragments-edit
                    data-sortable-ignore
                    class="bg-primary-50 font-medium text-grey-900 py-1 px-2 rounded-lg"
                    href="{{ $allowedFragment['manager']->route('fragment-create', $owner) }}"
                >
                    {{ ucfirst($allowedFragment['model']->adminLabel('label')) }}
                </a>
            @empty
                No available fragments.
            @endforelse
        </div>

        @if(count($sharedFragments) > 0)
            <div>
                <p class="font-medium text-grey-700 text-center">
                    Kies uit één van de gedeelde blokken
                </p>
            </div>
            <div class="flex justify-center items-center space-x-2">
                @foreach($sharedFragments as $sharedFragment)
                    <span
                        data-sortable-ignore
                        data-fragments-add="{{ $sharedFragment['manager']->route('fragment-add', $owner, $sharedFragment['model']) }}"
                        class="bg-primary-50 font-medium text-grey-900 py-1 px-2 rounded-lg"
                    >
                {{ ucfirst($sharedFragment['model']->adminLabel('title')) }}
                        {{ ($sharedFragment['model']->adminLabel('label')) }}
            </span>
                @endforeach
            </div>
        @endif
    </div>
</div>
