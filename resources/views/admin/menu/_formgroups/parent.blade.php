<x-chief::input.group rule="allow_parent" x-data="{ type: '{{ !!old('parent_id', $menuitem->parent_id) }}' }">
    <x-chief::form.label required>Niveau</x-chief::form.label>

    <x-chief::form.description>
        Zet dit item op het hoogste niveau of plaats het onder een bestaand.
    </x-chief::form.description>

    <div class="space-y-2">
        <div class="flex items-start gap-2">
            <x-chief::input.radio
                id="without-parent-id"
                name="allow_parent"
                value="0"
                :checked="!old('parent_id', $menuitem->parent_id)"
                x-on:click="type = '0'"
            />

            <x-chief::form.label for="without-parent-id" unset class="body body-dark leading-5">
                Geef dit menu item weer op het hoogste niveau
            </x-chief::form.label>
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

                <x-chief::form.label for="parent-id" unset class="body body-dark leading-5">
                    Selecteer het menu item waaronder dit item behoort
                </x-chief::form.label>
            </div>

            <div x-cloak x-show="type == '1'">
                <x-chief::input.group rule="parent_id">
                    <x-chief::multiselect
                        name="parent_id"
                        :options="$parents"
                        :selection="old('parent_id', $menuitem->parent_id)"
                        placeholder="Kies het bovenliggende menu item"
                    />
                </x-chief::input.group>
            </div>
        </div>
    </div>
</x-chief::input.group>
