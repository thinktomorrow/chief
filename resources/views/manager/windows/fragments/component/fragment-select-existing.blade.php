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

    <div class="space-y-3">
        @forelse($sharedFragments as $sharedFragment)
            <div data-form data-form-tags="fragments">
                <form
                    method="POST"
                    action="{{ $sharedFragment['manager']->route('fragment-add', $owner, $sharedFragment['model']) . (isset($order) ? '?order=' . $order : '') }}"
                >
                    <button
                        class="w-full p-3 space-y-3 text-left transition-all duration-75 ease-in-out border rounded-lg border-grey-100 bg-grey-50 hover:shadow-card hover:border-primary-500"
                        type="submit"
                    >
                        <div>
                            <span class="display-dark display-base">
                                {{ ucfirst($sharedFragment['resource']->getLabel()) }}
                            </span>
                        </div>

                        {!! $sharedFragment['model']->renderAdminFragment($owner, $loop) !!}
                    </button>
                </form>
            </div>
        @empty
            <div>
                <p class="body-base body-dark">Geen fragmenten gevonden.</p>
            </div>
        @endforelse
    </div>
</div>
