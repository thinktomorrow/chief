@formgroup
    @slot('label', 'Niveau')
    @slot('description', 'Zet dit item op het hoogste niveau of plaats het onder een bestaand.')

    <radio-options inline-template :errors="errors" default-type="{{ !!old('parent_id', $menuitem->parent_id) ? '1' : '0' }}">
        <div class="space-y-3 prose prose-dark">
            <label for="withoutParentId" class="flex items-center space-x-2 cursor-pointer">
                <input
                    id="withoutParentId"
                    name="allow_parent"
                    type="radio"
                    value="0"
                    v-on:click="changeType('0')" {{ !old('parent_id', $menuitem->parent_id) ? 'checked="checked"' : '' }}
                >

                <span>Geef dit menu item weer op het hoogste niveau</span>
            </label>

            <label for="parentId" class="block cursor-pointer space-y-3">
                <div class="flex items-center space-x-2">
                    <input
                        id="parentId"
                        name="allow_parent"
                        type="radio"
                        value="1"
                        v-on:click="changeType('1')" {{ !!old('parent_id', $menuitem->parent_id) ? 'checked="checked"' : '' }}
                    >

                    <span>Selecteer het menu item waaronder dit item behoort</span>
                </div>

                <div v-if="type == '1'" class="relative">
                    <chief-multiselect
                        name="parent_id"
                        :options='@json($parents)'
                        selected='@json(old('parent_id', $menuitem->parent_id))'
                        labelkey="label"
                        valuekey="id"
                        placeholder="Kies het bovenliggende menuitem"
                    ></chief-multiselect>
                </div>
            </label>
        </div>
    </radio-options>
@endformgroup
