@formgroup
    @slot('label', 'Sortering')
    @slot('description', 'Sortering van dit menu item op het huidige niveau.')

    <div class="space-y-3 prose prose-dark">
        <input
            type="number"
            name="order"
            id="order"
            placeholder="Menu order"
            value="{{ old('order', $menuitem->order) }}"
            class="input"
        >

        <p>Huidige sortering op dit niveau</p>

        <div class="border border-grey-150 divide-y divide-grey-150 rounded-lg">
            @foreach($menuitem->siblingsIncludingSelf() as $sibling)
                <div class="px-6 py-3 space-x-6 {{ $sibling->id == $menuitem->id ? 'bg-primary-50 text-primary-900 font-medium' : 'bg-white text-grey-800' }}">
                    <span class="font-semibold">{{ $sibling->order }}</span>

                    <span>{{ $sibling->label }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endformgroup
