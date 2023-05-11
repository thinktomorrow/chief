<x-chief::input.group>
    <x-chief::input.label required>
        Niveau
    </x-chief::input.label>

    <x-chief::input.description>
        Zet dit item op het hoogste niveau of plaats het onder een bestaand.
    </x-chief::input.description>

    <radio-options inline-template :errors="errors" default-type="{{ !!old('parent_id', $menuitem->parent_id) ? '1' : '0' }}">
        <div class="space-y-3">
            <label for="withoutParentId" class="flex items-start gap-2">
                <input
                    type="radio"
                    id="withoutParentId"
                    name="allow_parent"
                    value="0"
                    v-on:click="changeType('0')" {{ !old('parent_id', $menuitem->parent_id) ? 'checked="checked"' : null }}
                    class="form-input-radio"
                >

                <span class="body-dark">Geef dit menu item weer op het hoogste niveau</span>
            </label>

            <div class="space-y-2">
                <label for="parentId" class="flex items-start gap-2">
                    <input
                        type="radio"
                        id="parentId"
                        name="allow_parent"
                        value="1"
                        v-on:click="changeType('1')" {{ !!old('parent_id', $menuitem->parent_id) ? 'checked="checked"' : null }}
                        class="form-input-radio"
                    >

                    <span class="body-dark">Selecteer het menu item waaronder dit item behoort</span>
                </label>

                <div v-if="type == '1'">
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
    </radio-options>
</x-chief::input.group>
