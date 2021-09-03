<x-chief-formgroup label="Naam" name="name" isRequired>
    <x-slot name="description">
        <p>Unieke benaming van de rol.</p>
    </x-slot>

    <input type="text" name="name" value="{{ old('name', $role->name) }}">
</x-chief-formgroup>

<x-chief-formgroup label="Rechten" isRequired>
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

        @if($errors->has('permission_names.0'))
            <x-chief-inline-notification type="error">
                {{ $errors->first('permission_names.0') }}
            </x-chief-inline-notification>
        @endif
    </div>
</x-chief-formgroup>
