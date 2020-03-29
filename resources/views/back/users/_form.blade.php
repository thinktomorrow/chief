
@formgroup(['field' => ['firstname', 'lastname']])
    @slot('label', 'Naam')
    <div class="row gutter">
        <div class="column-5">
            <label for="firstName">Voornaam</label>
            <input id="firstName" class="input inset-s" type="text" name="firstname" value="{{ old('firstname',$user->firstname) }}">
        </div>
        <div class="column-7">
            <label for="lastName">Achternaam</label>
            <input id="lastName" class="input inset-s" type="text" name="lastname" value="{{ old('lastname',$user->lastname) }}">
        </div>
    </div>
@endformgroup

@formgroup(['field' => 'email'])
    @slot('label', 'E-mail')
    @slot('description', 'Dit e-mail adres geldt tevens als login.')
    <label for="email">E-mail</label>
    <input id="email" class="input inset-s" type="email" name="email" value="{{ old('email',$user->email) }}">
@endformgroup

@formgroup(['field' => 'roles'])
    @slot('label', 'Rechten')
    @slot('description', 'Geef aan met een of meerdere rollen welke rechten deze gebruiker ter beschikking heeft.')
        <label for="roles">Rechten</label>
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
@endformgroup
