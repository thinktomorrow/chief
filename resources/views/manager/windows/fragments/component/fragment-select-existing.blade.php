<div class="space-y-6">
    <p class="text-lg display-base display-dark">Kies een bestaand fragment</p>

    <form
        id="fragment-select-existing-form"
        action="{{ $ownerManager->route('fragments-select-existing', $owner) }}"
    >
        <input name="order" type="number" hidden value="{{ $order ?? 0 }}">

        <div data-vue-fields class="flex items-center gap-4">
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
                    class="w-full"
                ></chief-multiselect>
            @endif

            @php
                $existingTypesOptions = [];
                foreach($owner->allowedFragments() as $allowedFragmentClass) {
                    $existingTypesOptions[$allowedFragmentClass] = ucfirst(app($allowedFragmentClass)->adminConfig()->getModelName());
                }
            @endphp

            <chief-multiselect
                id="selectExistingTypes"
                name="types[]"
                placeholder="Kies een type"
                :options='@json($existingTypesOptions)'
                class="w-full"
            ></chief-multiselect>

            {{-- Should be deleted once the onchange/oninput submit on this form works --}}
            <button class="btn btn-primary" type="submit">Zoek</button>
        </div>
    </form>

    <div data-sidebar-component="existingFragments" class="-mx-6 divide-y-2 divide-dashed divide-primary-50">
        @forelse($sharedFragments as $sharedFragment)
            <div
                data-sidebar-close
                data-fragments-add="{{ $sharedFragment['manager']->route('fragment-add', $owner, $sharedFragment['model']) . (isset($order) ? '?order=' . $order : '') }}"
                class="p-6 space-y-2 overflow-hidden cursor-pointer group hover:bg-primary-500 transition-75"
            >
                <div>
                    <span class="display-dark display-base group-hover:text-white transition-75">
                        {{ ucfirst($sharedFragment['model']->adminConfig()->getModelName()) }}
                    </span>
                </div>

                {!! $sharedFragment['model']->renderAdminFragment($owner, $loop) !!}
            </div>
        @empty
            <p class="p-6">
                Geen fragmenten gevonden.
            </p>
        @endforelse
    </div>
</div>
