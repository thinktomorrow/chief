<div class="space-y-6">
    <p class="text-lg display-base display-dark">Kies een bestaand fragment</p>

    <form
        id="fragment-select-existing-form"
        action="{{ $ownerManager->route('fragments-select-existing', $owner) }}"
    >
        <input name="order" type="number" hidden value="{{ $order ?? 0 }}">

        <div data-vue-fields class="flex items-center gap-4">
            @if(public_method_exists($owner, 'getRelatedOwners'))
                <chief-multiselect
                    id="selectExistingOwners"
                    name="owners[]"
                    placeholder="Kies een pagina"
                    :options='@json($existingOwnersOptions)'
                    class="w-full"
                ></chief-multiselect>
            @endif

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

    <div class="-mx-6 divide-y divide-grey-100">
        @forelse($sharedFragments as $sharedFragment)

            <div data-form data-form-tags="fragments">
                <form method="POST" action="{{ $sharedFragment['manager']->route('fragment-add', $owner, $sharedFragment['model']) . (isset($order) ? '?order=' . $order : '') }}">
                    <button class="block w-full text-left p-6 space-y-2 overflow-hidden cursor-pointer group hover:bg-primary-500 transition-75" type="submit">
                        <div>
                            <span class="display-dark display-base group-hover:text-white transition-75">
                                {{ ucfirst($sharedFragment['resource']->getLabel()) }}
                            </span>
                        </div>
                        {!! $sharedFragment['model']->renderAdminFragment($owner, $loop) !!}
                    </button>
                </form>
            </div>
        @empty
            <p class="p-6">
                Geen fragmenten gevonden.
            </p>
        @endforelse
    </div>
</div>
