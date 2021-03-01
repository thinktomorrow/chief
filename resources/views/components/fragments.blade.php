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
         data-sortable-endpoint="@adminRoute('fragments-reorder', $ownerModel)"
         class="relative divide-y divide-grey-150 border-t border-b border-grey-150 -mx-12"
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

    <div hidden id="js-fragment-selection-template">
        <div
            data-sortable-handle
            data-sortable-id="remove-before-post"
            data-fragments-new-selection
            class="px-12 py-12"
        >
            <div class="pop space-y-4">
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
    </div>

    <div hidden id="js-fragment-add-template">
        <div
            data-fragments-new-trigger
            data-sortable-ignore
            data-sortable-id="remove-before-post"
            class="relative flex justify-center z-1 border-none"
        >
            <div
                class="absolute link link-black cursor-pointer bg-white rounded-full transition-150"
                style="margin-top: -12px; transform: scale(0);"
            >
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <!-- end fragment selection template -->

    </div>

</div>
