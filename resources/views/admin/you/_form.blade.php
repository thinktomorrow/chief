<x-chief-forms::formgroup.wrapper id="firstname" label="Voornaam" required>
    <input id="firstname" type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}">
    <x-chief-forms::formgroup.error error-ids="firstname"></x-chief-forms::formgroup.error>
</x-chief-forms::formgroup.wrapper>

<x-chief-forms::formgroup.wrapper id="lastname" label="Achternaam" required>
    <input id="lastname" type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}">
    <x-chief-forms::formgroup.error error-ids="lastname"></x-chief-forms::formgroup.error>
</x-chief-forms::formgroup.wrapper>

<x-chief-forms::formgroup.wrapper id="email" label="E-mail (tevens login)" required>
    <x-slot name="description">
        <p>Dit e-mail adres geldt tevens als login.</p>
    </x-slot>

    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}">
    <x-chief-forms::formgroup.error error-ids="email"></x-chief-forms::formgroup.error>
</x-chief-forms::formgroup.wrapper>
