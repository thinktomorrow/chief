<x-chief-form::formgroup id="firstname" label="Voornaam" required>
    <input id="firstname" type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}">
    <x-chief-form::formgroup.error error-ids="firstname"/>
</x-chief-form::formgroup>

<x-chief-form::formgroup id="lastname" label="Achternaam" required>
    <input id="lastname" type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}">
    <x-chief-form::formgroup.error error-ids="lastname"/>
</x-chief-form::formgroup>

<x-chief-form::formgroup id="email" label="E-mail (tevens login)" required>
    <x-slot name="description">
        Dit e-mailadres geldt tevens als login.
    </x-slot>

    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}">
    <x-chief-form::formgroup.error error-ids="email"/>
</x-chief-form::formgroup>
