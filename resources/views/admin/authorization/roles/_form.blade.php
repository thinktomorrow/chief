<x-chief::field label="Naam" error="name" isRequired>
    <x-slot name="description">
        <p>Unieke benaming van de rol.</p>
    </x-slot>

    <input type="text" name="name" value="{{ old('name', $role->name) }}">
</x-chief::field>

<x-chief::field label="Rechten" error="permission_names.0" isRequired>
    <x-slot name="description">
        <p>Met welke rechten heeft deze rol toegang tot de admin.</p>
    </x-slot>

    <div class="space-y-2">
        <chief-multiselect
            name="permission_names"
            :options=@json($permission_names)
            selected='@json(old('permission_names', $role->permissionNames()))'
            :multiple="true"
        ></chief-multiselect>
    </div>
</x-chief::field>
