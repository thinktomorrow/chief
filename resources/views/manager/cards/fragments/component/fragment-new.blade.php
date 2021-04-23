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
                        {{ ucfirst($allowedFragment['model']->adminConfig()->getIndexTitle()) }}
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
                    {{ ucfirst($sharedFragment['model']->adminConfig()->getPageTitle()) }}
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
</div>
