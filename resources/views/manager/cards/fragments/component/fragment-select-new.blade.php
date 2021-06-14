<div class="space-y-12">
    <h3>Voeg een nieuw fragment toe</h3>

    <div class="row-start-center gutter-1">
        @forelse($fragments as $allowedFragment)
            <div>
                <a
                    data-sidebar-trigger="fragments"
                    class="inline-block label label-primary label-lg"
                    href="{{ $allowedFragment['manager']->route('fragment-create', $owner) }}"
                >
                    {{ ucfirst($allowedFragment['model']->adminConfig()->getPageTitle()) }}

                    {{ $allowedFragment['model']->renderAdminFragment($owner, $loop) }}
                </a>
            </div>
        @empty
            No available fragments.
        @endforelse
    </div>
</div>
