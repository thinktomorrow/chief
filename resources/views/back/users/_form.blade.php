@chiefformgroup(['field' => 'email'])
    @slot('label', 'E-mail')
    @slot('description', 'Opgelet dit e-mail adres geldt tevens als login. Bij wijziging moet de gebruiker een nieuwe bevestiging via mail goedkeuren.')
    <input class="input inset-s" type="email" name="email" value="{{ old('email',$user->email) }}">
@endchiefformgroup

@chiefformgroup(['field' => 'firstname'])
    @slot('label', 'Voornaam')
    <input class="input inset-s" type="text" name="firstname" value="{{ old('firstname',$user->firstname) }}">
@endchiefformgroup

@chiefformgroup(['field' => 'lastname'])
    @slot('label', 'Achternaam')
    <input class="input inset-s" type="text" name="lastname" value="{{ old('lastname',$user->lastname) }}">
@endchiefformgroup

@chiefformgroup(['field' => 'roles'])
    @slot('label', 'Rechten')
    @slot('description', 'Geef aan met een of meerdere rollen welke rechten deze gebruiker ter beschikking heeft.')
    <chief-multiselect
            name="roles"
            :options=@json($roleNames)
                    selected='@json(old('roles', $user->roleNames()))'
            :multiple="true"
    >
    </chief-multiselect>
    @if($errors->has('roles.0'))
        <span class="caption">{{ $errors->first('roles.0') }}</span>
    @endif
@endchiefformgroup