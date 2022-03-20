<div data-form data-form-tags="fragments" class="space-y-6">
    <p class="text-lg display-base display-dark">Voeg een nieuw fragment toe</p>

    <div class="-mx-6 divide-y divide-grey-100">
        @forelse($fragments as $allowedFragment)
            <a
                data-sidebar-trigger
                href="{{ $allowedFragment['manager']->route('fragment-create', $owner) . (isset($order) ? '?order=' . $order : '') }}"
                title="{{ ucfirst($allowedFragment['resource']->getLabel()) }}"
                class="block p-6 space-y-2 overflow-hidden group hover:bg-primary-500 transition-75"
            >
                <div>
                    <span class="display-dark display-base group-hover:text-white transition-75">
                        {{ ucfirst($allowedFragment['resource']->getLabel()) }}
                    </span>
                </div>

                {!! $allowedFragment['model']->renderAdminFragment($owner, $loop) !!}
            </a>
        @empty
            <div class="p-6">
                <p class="body-base body-dark">Geen fragmenten gevonden.</p>
            </div>
        @endforelse
    </div>
</div>
