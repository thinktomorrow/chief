<div data-form data-form-tags="fragments" class="space-y-12">
    <h3 class="h3 display-dark">Voeg een nieuw fragment toe</h3>

    <div>
        <div class="-m-6 divide-y divide-grey-100">
            @forelse($fragments as $allowedFragment)
                <div class="p-6 group hover:bg-primary-500 transition-75 rounded-xl">
                    <a
                        data-sidebar-trigger
                        class="flex flex-col w-full space-y-2 overflow-hidden"
                        href="{{ $allowedFragment['manager']->route('fragment-create', $owner) . (isset($order) ? '?order=' . $order : '') }}"
                    >
                        <span class="font-semibold text-grey-900 group-hover:text-white transition-75">
                            {{ ucfirst($allowedFragment['model']->adminConfig()->getModelName()) }}
                        </span>

                        {!! $allowedFragment['model']->renderAdminFragment($owner, $loop) !!}
                    </a>
                </div>
            @empty
                <div class="p-6">
                    <p class="body-base body-dark">Geen fragmenten gevonden.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
