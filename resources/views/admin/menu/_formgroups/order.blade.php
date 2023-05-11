<x-chief::input.group>
    <x-chief::input.label for="order" required>
        Sortering
    </x-chief::input.label>

    <x-chief::input.description>
        Sortering van dit menu item op het huidige niveau.
    </x-chief::input.description>

    <div class="space-y-4">
        <x-chief::input.number
            id="order"
            name="order"
            placeholder="Menu order"
            value="{{ old('order', $menuitem->order) }}"
        />

        <div class="space-y-3">
            <x-chief::input.description>
                Huidige sortering op dit niveau:
            </x-chief::input.description>

            <div class="overflow-hidden border divide-y rounded-md border-grey-200 divide-grey-200">
                @foreach($menuitem->siblingsIncludingSelf() as $sibling)
                    <div @class([
                        'flex gap-3 px-3 py-2',
                        'bg-grey-100 h1-dark' => $sibling->id == $menuitem->id,
                        'bg-white body-dark' => $sibling->id != $menuitem->id,
                    ])>
                        <div class="w-10 shrink-0">
                            <span class="font-medium">{{ $sibling->order }}</span>
                        </div>

                        <div>{{ $sibling->label }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-chief::input.group>
