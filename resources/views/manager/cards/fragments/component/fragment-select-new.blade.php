<div class="space-y-12">
    <h3>Creëer een nieuw fragment</h3>

    <div class="row-start-center gutter-1">
        @forelse($fragments as $allowedFragment)
            <span>
                <a
                    data-sidebar-fragments-edit
                    data-sidebar-close
                    class="bg-grey-100 rounded-md p-4"
                    href="{{ $allowedFragment['manager']->route('fragment-create', $owner) }}"
                >
                    {{ ucfirst($allowedFragment['model']->adminConfig()->getIndexTitle()) }}
                </a>
            </span>
        @empty
            No available fragments.
        @endforelse
    </div>
</div>