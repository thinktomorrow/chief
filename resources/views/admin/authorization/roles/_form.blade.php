<x-chief::form.fieldset rule="name">
    <x-chief::form.label for="name" required>Naam</x-chief::form.label>

    <x-chief::form.description>Unieke benaming van de rol.</x-chief::form.description>

    <x-chief::form.input.text id="name" name="name" value="{{ old('name', $role->name) }}" />
</x-chief::form.fieldset>

<x-chief::form.fieldset rule="permission_names">
    <x-chief::form.label for="permission_names" required>Toestemmingen</x-chief::form.label>

    <x-chief::form.description>Met welke rechten heeft deze rol toegang tot de admin.</x-chief::form.description>

    <x-chief::multiselect
        id="permission_names"
        name="permission_names"
        :options="$permission_names"
        :selection="old('permission_names', $role->permissionNames())"
        :multiple="true"
    />
</x-chief::form.fieldset>
