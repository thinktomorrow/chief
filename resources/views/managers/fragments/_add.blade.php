<div class="w-full space-y-2">
    @forelse($allowedFragments as $allowedFragment)
        <div>
            <a
                data-sidebar-fragments-edit
                class="link link-primary"
                href="{{ $allowedFragment['manager']->route('fragment-create', $owner) }}"
            >
                <x-link-label type="add">
                    Voeg een {{ $allowedFragment['model']->adminLabel('label') }} toe
                </x-link-label>
            </a>
        </div>
    @empty
        No available fragments.
    @endforelse
</div>
