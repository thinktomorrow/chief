<x-chief::field.form label="Voornaam" id="firstname" error="firstname" isRequired>
    <input id="firstname" type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}">
</x-chief::field.form>

<x-chief::field.form label="Achternaam" id="lastname" error="lastname" isRequired>
    <input id="lastname" type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}">
</x-chief::field.form>

<x-chief::field.form label="E-mail" id="email" error="email" isRequired>
    <x-slot name="description">
        <p>Dit e-mailadres geldt tevens als jouw login.</p>
    </x-slot>

    <input id="email" type="email" name="email" value="{{ old('email',$user->email) }}">
</x-chief::field.form>
