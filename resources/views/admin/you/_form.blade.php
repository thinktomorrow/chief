<x-chief-formgroup label="Voornaam" id="firstname" name="firstname" isRequired>
    <input id="firstname" type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}">
</x-chief-formgroup>

<x-chief-formgroup label="Achternaam" id="lastname" name="lastname" isRequired>
    <input id="lastname" type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}">
</x-chief-formgroup>

<x-chief-formgroup label="E-mail" id="email" name="email" isRequired>
    <x-slot name="description">
        <p>Dit e-mailadres geldt tevens als jouw login.</p>
    </x-slot>

    <input id="email" type="email" name="email" value="{{ old('email',$user->email) }}">
</x-chief-formgroup>
