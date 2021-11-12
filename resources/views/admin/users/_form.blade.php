<div>
    <div class="row gutter-4">
        <x-chief::field.form label="Voornaam" id="firstname" error="firstname" class="w-full lg:w-1/2" isRequired>
            <input id="firstname" type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}">
        </x-chief::field.form>

        <x-chief::field.form label="Achternaam" id="lastname" error="lastname" class="w-full lg:w-1/2" isRequired>
            <input id="lastname" type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}">
        </x-chief::field.form>
    </div>
</div>

<x-chief::field.form label="E-mail" id="email" error="email" isRequired>
    <x-slot name="description">
        <p>Dit e-mail adres geldt tevens als login.</p>
    </x-slot>

    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}">
</x-chief::field.form>

<x-chief::field.form label="Rechten" error="roles.0" isRequired>
    <x-slot name="description">
        <p>Geef aan met een of meerdere rollen welke rechten deze gebruiker ter beschikking heeft.</p>
    </x-slot>

    <chief-multiselect
        name="roles"
        :options=@json($roleNames)
        selected='@json(old('roles', $user->roleNames()))'
        :multiple="true"
    ></chief-multiselect>
</x-chief::field.form>
