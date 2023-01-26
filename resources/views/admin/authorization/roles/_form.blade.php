<x-chief-form::formgroup id="name" label="Naam" required>
    <x-slot name="description">
        Unieke benaming van de rol.
    </x-slot>

    <input id="name" type="text" name="name" value="{{ old('name', $role->name) }}">

    <x-chief-form::formgroup.error error-ids="name"/>
</x-chief-form::formgroup>

<x-chief-form::formgroup id="roles" label="Toestemmingen" required>
    <x-slot name="description">
        Met welke rechten heeft deze rol toegang tot de admin.
    </x-slot>

    <div class="space-y-2">
        <chief-multiselect
            name="permission_names"
            :options=@json($permission_names)
            selected='@json(old('permission_names', $role->permissionNames()))'
            :multiple="true"
        />
    </div>

    <x-chief-form::formgroup.error error-ids="roles"/>
</x-chief-form::formgroup>
