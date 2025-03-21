<x-chief::form.fieldset>
    <x-chief::form.label for="order" required>Sortering</x-chief::form.label>

    <x-chief::form.description>Sortering van dit menu item op het huidige niveau.</x-chief::form.description>

    <div class="space-y-4">
        <x-chief::input.number
            id="order"
            name="order"
            placeholder="Menu order"
            value="{{ old('order', $menuitem->order) }}"
        />

        <div class="space-y-3">
            <x-chief::form.description>Huidige sortering op dit niveau:</x-chief::form.description>

            <div class="divide-y divide-grey-200 overflow-hidden rounded-md border border-grey-200">
                @foreach ($menuitem->siblingsIncludingSelf() as $sibling)
                    <div
                        @class([
                            'flex gap-3 px-3 py-2',
                            'h1-dark bg-grey-100' => $sibling->id == $menuitem->id,
                            'body-dark bg-white' => $sibling->id != $menuitem->id,
                        ])
                    >
                        <div class="w-10 shrink-0">
                            <span class="font-medium">{{ $sibling->order }}</span>
                        </div>

                        <div>{{ $sibling->label }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-chief::form.fieldset>
