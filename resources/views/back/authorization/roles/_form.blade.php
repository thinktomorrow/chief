@chiefformgroup(['field' => 'name'])
    @slot('label', 'Naam')
    @slot('description', 'Unieke benaming van de rol')
    <input class="input inset-s" type="text" name="name" value="{{ old('name',$role->name) }}">
@endchiefformgroup

@chiefformgroup(['field' => 'description'])
    @slot('label', 'Beschrijving')
    @slot('description', 'Wat houdt deze rol voor jullie in?')
    <input class="input inset-s" type="text" name="name" value="{{ old('name',$role->name) }}">
@endchiefformgroup

@chiefformgroup(['field' => 'permission_names'])
    @slot('label', 'Rechten')
    @slot('description', 'Met welke rechten heeft deze rol toegang tot de admin')
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
@endchiefformgroup