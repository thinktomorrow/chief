<div class="space-y-12">
    <h3>Kies een bestaand fragment</h3>

    <form
        id="fragment-select-existing-form"
        action="{{ $ownerManager->route('fragments-select-existing', $owner) }}"
    >
        <div data-vue-fields class="flex items-center -mx-2">
            @if(public_method_exists($owner, 'getRelatedOwners'))
                @php
                    $existingOwnersOptions = [];
                    foreach($owner->getRelatedOwners() as $relatedOwner) {
                        $existingOwnersOptions[$relatedOwner->modelReference()->get()] = $relatedOwner->adminConfig()->getPageTitle();
                    }
                @endphp

                <chief-multiselect
                    id="selectExistingOwners"
                    name="owners[]"
                    placeholder="Kies een pagina"
                    :options='@json($existingOwnersOptions)'
                    class="w-1/2 px-2"
                ></chief-multiselect>
            @endif

            @php
                $existingTypesOptions = [];
                foreach($owner->allowedFragments() as $allowedFragmentClass) {
                    $existingTypesOptions[$allowedFragmentClass] = (new $allowedFragmentClass)->adminConfig()->getModelName();
                }
            @endphp

            <chief-multiselect
                id="selectExistingTypes"
                name="types[]"
                placeholder="Kies een type"
                :options='@json($existingTypesOptions)'
                class="w-1/2 px-2"
            ></chief-multiselect>
        </div>


        {{-- Should be deleted once the onchange/oninput submit on this form works --}}
        <button class="mt-4 btn btn-primary" type="submit">Filter</button>
    </form>

    <div data-sidebar-component="existingFragments">
        <div class="-m-6 divide-y divide-grey-100">
            @forelse($sharedFragments as $sharedFragment)
                <div class="p-6 group hover:bg-primary-500 transition-75 rounded-xl">
                    <a
                        data-sidebar-close
                        data-fragments-add="{{ $sharedFragment['manager']->route('fragment-add', $owner, $sharedFragment['model']) . (isset($order) ? '?order=' . $order : '') }}"
                        class="flex flex-col w-full space-y-2 overflow-hidden"
                    >
                        <span class="font-semibold text-grey-900 group-hover:text-white transition-75">
                            {{ ucfirst($sharedFragment['model']->adminConfig()->getModelName()) }}
                        </span>

                        {!! $sharedFragment['model']->renderAdminFragment($owner, $loop) !!}
                    </a>
                </div>
            @empty
                Geen fragmenten gevonden.
            @endforelse
        </div>
    </div>
</div>
