<x-chief::form.fieldset rule="firstname">
    <x-chief::form.label for="firstname" required>Voornaam</x-chief::form.label>
    <x-chief::form.input.text id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}" />
</x-chief::form.fieldset>

<x-chief::form.fieldset rule="lastname">
    <x-chief::form.label for="lastname" required>Achternaam</x-chief::form.label>
    <x-chief::form.input.text id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" />
</x-chief::form.fieldset>

<x-chief::form.fieldset rule="email">
    <x-chief::form.label for="email" required>Achternaam</x-chief::form.label>
    <x-chief::form.description>Dit e-mailadres geldt tevens als login.</x-chief::form.description>
    <x-chief::form.input.email id="email" name="email" value="{{ old('email', $user->email) }}" />
</x-chief::form.fieldset>

<x-chief::button data-slot="form-group" form="updateForm" type="submit" variant="blue">Bewaar</x-chief::button>
