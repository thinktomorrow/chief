<div class="space-y-12">
    <h3>Voeg een nieuw fragment toe</h3>

    <div class="row-start-start gutter-4">
        @forelse($fragments as $allowedFragment)
            <a
                data-sidebar-trigger="fragments"
                class="flex flex-col w-1/3 space-y-1 overflow-hidden rounded-lg group hover:bg-primary-500 wireframe-icon"
                href="{{ $allowedFragment['manager']->route('fragment-create', $owner) }}"
            >
                <span class="font-semibold text-grey-900 group-hover:text-white">
                    {{ ucfirst($allowedFragment['model']->adminConfig()->getPageTitle()) }}
                </span>

                {{ $allowedFragment['model']->renderAdminFragment($owner, $loop) }}
            </a>
        @empty
            No available fragments.
        @endforelse
    </div>
</div>
