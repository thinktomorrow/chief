<div>
    <div class="row gutter-4">
        <x-chief-formgroup label="Voornaam" id="firstname" name="firstname" class="w-full lg:w-1/2" isRequired>
            <input id="firstname" type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}">
        </x-chief-formgroup>

        <x-chief-formgroup label="Achternaam" id="lastname" name="lastname" class="w-full lg:w-1/2" isRequired>
            <input id="lastname" type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}">
        </x-chief-formgroup>
    </div>
</div>

<x-chief-formgroup label="E-mail" id="email" name="email" isRequired>
    <x-slot name="description">
        <p>Dit e-mail adres geldt tevens als login.</p>
    </x-slot>

    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}">
</x-chief-formgroup>

<x-chief-formgroup label="Rechten" isRequired>
    <x-slot name="description">
        <p>Geef aan met een of meerdere rollen welke rechten deze gebruiker ter beschikking heeft.</p>
    </x-slot>

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
</x-chief-formgroup>
