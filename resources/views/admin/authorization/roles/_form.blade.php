<x-chief-form::formgroup.wrapper id="name" label="Naam" required>
    <x-slot name="description">
        <p>Unieke benaming van de rol.</p>
    </x-slot>
    <input id="name" type="text" name="name" value="{{ old('name', $role->name) }}">
    <x-chief-form::formgroup.error error-ids="name"></x-chief-form::formgroup.error>
</x-chief-form::formgroup.wrapper>

<x-chief-form::formgroup.wrapper id="roles" label="Toestemmingen" required>
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

    <x-chief-form::formgroup.error error-ids="roles"></x-chief-form::formgroup.error>
</x-chief-form::formgroup.wrapper>
