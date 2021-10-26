<div>
    <div class="row gutter-4">
        <x-chief::field label="Voornaam" id="firstname" error="firstname" class="w-full lg:w-1/2" isRequired>
            <input id="firstname" type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}">
        </x-chief::field>

        <x-chief::field label="Achternaam" id="lastname" error="lastname" class="w-full lg:w-1/2" isRequired>
            <input id="lastname" type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}">
        </x-chief::field>
    </div>
</div>

<x-chief::field label="E-mail" id="email" error="email" isRequired>
    <x-slot name="description">
        <p>Dit e-mail adres geldt tevens als login.</p>
    </x-slot>

    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}">
</x-chief::field>

<x-chief::field label="Rechten" error="roles.0" isRequired>
    <x-slot name="description">
        <p>Geef aan met een of meerdere rollen welke rechten deze gebruiker ter beschikking heeft.</p>
    </x-slot>

    <chief-multiselect
        name="roles"
        :options=@json($roleNames)
        selected='@json(old('roles', $user->roleNames()))'
        :multiple="true"
    ></chief-multiselect>
</x-chief::field>
