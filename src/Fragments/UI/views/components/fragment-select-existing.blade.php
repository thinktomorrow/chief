<div class="pt-6 space-y-6 border-t border-grey-100">
    <p class="text-lg leading-none h6 h1-dark">Kies een bestaand fragment</p>

    <div data-form>
        <form
            id="fragment-select-existing-form"
            action="{{ $ownerManager->route('fragments-select-existing', $owner) }}"
        >
            <input name="order" type="number" hidden value="{{ $order ?? 0 }}">

            <div class="flex items-center gap-4">
                @if(public_method_exists($owner, 'getRelatedOwners'))
                    <x-chief::multiselect
                        id="selectExistingOwners"
                        name="owners[]"
                        placeholder="Kies een pagina"
                        :options="$existingOwnersOptions"
                        class="w-1/2"
                    ></x-chief::multiselect>
                @endif

                <x-chief::multiselect
                    id="selectExistingTypes"
                    name="types[]"
                    placeholder="Kies een type"
                    :options="$existingTypesOptions"
                    class="w-1/2"
                ></x-chief::multiselect>

                {{-- Should be deleted once the onchange/oninput submit on this form works --}}
                <button class="btn btn-primary" type="submit">Zoek</button>
            </div>
        </form>
    </div>

    <div data-sidebar-component="existingFragments" class="space-y-3">
        @forelse($sharedFragments as $sharedFragment)
            <div data-form data-form-tags="fragments">
                <form
                    method="POST"
                    action="{{ route('chief::fragments.attach', [$context->id, $sharedFragment->getFragmentId()]) . (isset($order) ? '?order=' . $order : '') }}"
                >
                    <button
                        class="w-full p-3 space-y-3 text-left transition-all duration-75 ease-in-out border rounded-lg border-grey-100 bg-grey-50 hover:shadow-card hover:border-primary-500"
                        type="submit"
                    >
                        <div>
                            <span class="h6-dark h6">
                                {{ ucfirst($sharedFragment->getLabel()) }}
                            </span>
                        </div>

                        {!! $sharedFragment->renderAdminFragment($owner, $loop) !!}
                    </button>
                </form>
            </div>
        @empty
            <div>
                <p class="body body-dark">Geen fragmenten gevonden.</p>
            </div>
        @endforelse
    </div>
</div>
