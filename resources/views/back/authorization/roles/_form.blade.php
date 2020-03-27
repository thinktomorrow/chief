@formgroup(['field' => 'name'])
    @slot('label', 'Naam')
    @slot('description', 'Unieke benaming van de rol')
    @slot('isRequired', $field->required())
    <input class="input inset-s" type="text" name="name" value="{{ old('name',$role->name) }}">
@endformgroup

@formgroup(['field' => 'permission_names'])
    @slot('label', 'Rechten')
    @slot('description', 'Met welke rechten heeft deze rol toegang tot de admin')
    @slot('isRequired', $field->required())
    <chief-multiselect
        name="permission_names"
        :options=@json($permission_names)
        selected='@json(old('permission_names', $role->permissionNames()))'
        :multiple="true"
    >
    </chief-multiselect>
    @if($errors->has('permission_names.0'))
        <span class="caption">{{ $errors->first('permission_names.0') }}</span>
    @endif
@endformgroup
