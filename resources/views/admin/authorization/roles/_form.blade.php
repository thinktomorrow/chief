<x-chief::input.group rule="name">
    <x-chief::input.label for="name" required>
        Naam
    </x-chief::input.label>

    <x-chief::input.description>
        Unieke benaming van de rol.
    </x-chief::input.description>

    <x-chief::input.text id="name" name="name" value="{{ old('name', $role->name) }}"/>
</x-chief::input.group>

<x-chief::input.group rule="permission_names">
    <x-chief::input.label for="permission_names" required>
        Toestemmingen
    </x-chief::input.label>

    <x-chief::input.description>
        Met welke rechten heeft deze rol toegang tot de admin.
    </x-chief::input.description>

    <chief-multiselect
        id="permission_names"
        name="permission_names"
        :options=@json($permission_names)
        selected='@json(old('permission_names', $role->permissionNames()))'
        :multiple="true"
    />
</x-chief::input.group>
