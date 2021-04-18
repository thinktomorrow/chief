@formgroup(['field' => 'name'])
    @slot('label', 'Naam')
    @slot('description', 'Unieke benaming van de rol')
    @slot('isRequired', true)

    <input class="w-full" type="text" name="name" value="{{ old('name', $role->name) }}">
@endformgroup

@formgroup(['field' => 'permission_names'])
    @slot('label', 'Rechten')
    @slot('description', 'Met welke rechten heeft deze rol toegang tot de admin')
    @slot('isRequired', true)

    <div class="space-y-2">
        <chief-multiselect
            name="permission_names"
            :options=@json($permission_names)
            selected='@json(old('permission_names', $role->permissionNames()))'
            :multiple="true"
        ></chief-multiselect>

        @if($errors->has('permission_names.0'))
            <x-inline-notification type="error">
                {{ $errors->first('permission_names.0') }}
            </x-inline-notification>
        @endif
    </div>
@endformgroup
