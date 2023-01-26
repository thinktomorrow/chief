<x-chief-form::formgroup id="niveau" label="Niveau" required>
    <x-slot name="description">
        Zet dit item op het hoogste niveau of plaats het onder een bestaand.
    </x-slot>

    <radio-options inline-template :errors="errors" default-type="{{ !!old('parent_id', $menuitem->parent_id) ? '1' : '0' }}">
        <div class="space-y-3">
            <label for="withoutParentId" class="with-radio">
                <input
                    id="withoutParentId"
                    name="allow_parent"
                    type="radio"
                    value="0"
                    v-on:click="changeType('0')" {{ !old('parent_id', $menuitem->parent_id) ? 'checked="checked"' : null }}
                >

                <span>Geef dit menu item weer op het hoogste niveau</span>
            </label>

            <div class="space-y-2">
                <label for="parentId" class="with-radio">
                    <input
                        id="parentId"
                        name="allow_parent"
                        type="radio"
                        value="1"
                        v-on:click="changeType('1')" {{ !!old('parent_id', $menuitem->parent_id) ? 'checked="checked"' : null }}
                    >

                    <span>Selecteer het menu item waaronder dit item behoort</span>
                </label>

                <div v-if="type == '1'">
                        <chief-multiselect
                            name="parent_id"
                            :options='@json($parents)'
                            selected='@json(old('parent_id', $menuitem->parent_id))'
                            labelkey="label"
                            valuekey="id"
                            placeholder="Kies het bovenliggende menu item"
                        ></chief-multiselect>
                        <x-chief-form::formgroup.error error-ids="parent_id"></x-chief-form::formgroup.error>
                </div>
            </div>
        </div>
    </radio-options>
</x-chief-form::formgroup>
