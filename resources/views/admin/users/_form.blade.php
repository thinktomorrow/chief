@formgroup(['field' => ['firstname', 'lastname']])
    @slot('label', 'Naam')
    @slot('isRequired', true)

    <div class="row gutter-2">
        <div class="w-full lg:w-1/2 flex flex-col space-y-2">
            <label for="firstName" class="font-medium text-grey-700">Voornaam</label>
            <input id="firstName" type="text" name="firstname" value="{{ old('firstname',$user->firstname) }}">
        </div>

        <div class="w-full lg:w-1/2 flex flex-col space-y-2">
            <label for="lastName" class="font-medium text-grey-700">Achternaam</label>
            <input id="lastName" type="text" name="lastname" value="{{ old('lastname',$user->lastname) }}">
        </div>
    </div>
@endformgroup

@formgroup(['field' => 'email'])
    @slot('label', 'E-mail')
    @slot('description', 'Dit e-mail adres geldt tevens als login.')
    @slot('isRequired', true)

    <input id="email" type="email" name="email" value="{{ old('email',$user->email) }}" class="w-full">
@endformgroup

@formgroup(['field' => 'roles'])
    @slot('label', 'Rechten')
    @slot('description', 'Geef aan met een of meerdere rollen welke rechten deze gebruiker ter beschikking heeft.')
    @slot('isRequired', true)

    <chief-multiselect
        name="roles"
        :options=@json($roleNames)
        selected='@json(old('roles', $user->roleNames()))'
        :multiple="true"
    ></chief-multiselect>

    @if($errors->has('roles.0'))
        <x-inline-notification type="error">
            {{ $errors->first('roles.0') }}
        </x-inline-notification>
    @endif
@endformgroup
