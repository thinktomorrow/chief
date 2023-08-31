<x-chief::input.group rule="allow_parent" x-data="{ type: '{{ !!old('parent_id', $menuitem->parent_id) }}' }">
    <x-chief::input.label required>
        Niveau
    </x-chief::input.label>

    <x-chief::input.description>
        Zet dit item op het hoogste niveau of plaats het onder een bestaand.
    </x-chief::input.description>

    <div class="space-y-3">
        <div class="flex items-start gap-2">
            <x-chief::input.radio
                id="without-parent-id"
                name="allow_parent"
                value="0"
                :checked="!old('parent_id', $menuitem->parent_id)"
                x-on:click="type = '0'"
            />

            <x-chief::input.label for="without-parent-id" unset class="body body-dark">
                Geef dit menu item weer op het hoogste niveau
            </x-chief::input.label>
        </div>

        <div class="space-y-2">
            <div class="flex items-start gap-2">
                <x-chief::input.radio
                    id="parent-id"
                    name="allow_parent"
                    value="1"
                    :checked="!!old('parent_id', $menuitem->parent_id)"
                    x-on:click="type = '1'"
                />

                <x-chief::input.label for="parent-id" unset class="body body-dark">
                    Selecteer het menu item waaronder dit item behoort
                </x-chief::input.label>
            </div>

            <div x-cloak x-show="type == '1'">
                <x-chief::input.group rule="parent_id">
                    <chief-multiselect
                        name="parent_id"
                        :options='@json($parents)'
                        selected='@json(old('parent_id', $menuitem->parent_id))'
                        labelkey="label"
                        valuekey="id"
                        placeholder="Kies het bovenliggende menu item"
                    />
                </x-chief::input.group>
            </div>
        </div>
    </div>
</x-chief::input.group>
