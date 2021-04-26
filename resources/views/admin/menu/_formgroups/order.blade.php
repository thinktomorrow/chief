<x-chief-formgroup label="Sortering" name="order">
    <x-slot name="description">
        <p>Sortering van dit menu item op het huidige niveau.</p>
    </x-slot>

    <div class="space-y-4">
        <input
            type="number"
            name="order"
            id="order"
            placeholder="Menu order"
            value="{{ old('order', $menuitem->order) }}"
        >

        <div class="space-y-4">
            <p class="text-grey-700">Huidige sortering op dit niveau</p>

            <div class="border border-grey-200 divide-y divide-grey-200 rounded-lg overflow-hidden">
                @foreach($menuitem->siblingsIncludingSelf() as $sibling)
                    <div class="px-6 py-3 space-x-6 {{ $sibling->id == $menuitem->id ? 'bg-primary-50 text-primary-900 font-medium' : 'bg-white text-grey-800' }}">
                        <span class="font-semibold">{{ $sibling->order }}</span>

                        <span>{{ $sibling->label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-chief-formgroup>
