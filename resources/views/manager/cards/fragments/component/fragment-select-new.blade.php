<div class="space-y-12">
    <h3>Voeg een nieuw fragment toe</h3>

    <div class="row-start-center gutter-1">
        @forelse($fragments as $allowedFragment)
            <span>
                <a
                    data-sidebar-trigger="fragments"
                    class="bg-grey-100 rounded-md p-4"
                    href="{{ $allowedFragment['manager']->route('fragment-create', $owner) }}"
                >
                    {{ ucfirst($allowedFragment['model']->adminConfig()->getModelName()) }}
                </a>
            </span>
        @empty
            No available fragments.
        @endforelse
    </div>
</div>
