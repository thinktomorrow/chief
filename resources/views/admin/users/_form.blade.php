<div>
    <div class="row gutter-4">
        <x-chief-form::formgroup.wrapper id="firstname" label="Voornaam" class="w-full lg:w-1/2" required>
            <input id="firstname" type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}">
            <x-chief-form::formgroup.error error-ids="firstname"></x-chief-form::formgroup.error>
        </x-chief-form::formgroup.wrapper>

        <x-chief-form::formgroup.wrapper id="lastname" label="Achternaam" class="w-full lg:w-1/2" required>
            <input id="lastname" type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}">
            <x-chief-form::formgroup.error error-ids="lastname"></x-chief-form::formgroup.error>
        </x-chief-form::formgroup.wrapper>
    </div>
</div>

<x-chief-form::formgroup.wrapper id="email" label="E-mail (tevens login)" required>
    <x-slot name="description">
        <p>Dit e-mail adres geldt tevens als login.</p>
    </x-slot>

    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}">
    <x-chief-form::formgroup.error error-ids="email"></x-chief-form::formgroup.error>
</x-chief-form::formgroup.wrapper>

<x-chief-form::formgroup.wrapper id="roles" label="Rechten" required>
    <x-slot name="description">
        <p>Geef aan met een of meerdere rollen welke rechten deze gebruiker ter beschikking heeft.</p>
    </x-slot>

        <chief-multiselect
            name="roles"
            :options=@json($roleNames)
            selected='@json(old('roles', $user->roleNames()))'
            :multiple="true"
        ></chief-multiselect>

    <x-chief-form::formgroup.error error-ids="roles"></x-chief-form::formgroup.error>
</x-chief-form::formgroup.wrapper>
