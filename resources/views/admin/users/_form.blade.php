<x-chief::form.fieldset rule="firstname">
    <x-chief::form.label for="firstname" required>Voornaam</x-chief::form.label>
    <x-chief::form.input.text id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}" />
</x-chief::form.fieldset>

<x-chief::form.fieldset rule="lastname">
    <x-chief::form.label for="lastname" required>Achternaam</x-chief::form.label>
    <x-chief::form.input.text id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" />
</x-chief::form.fieldset>

<x-chief::form.fieldset rule="email">
    <x-chief::form.label for="email" required>E-mailadres</x-chief::form.label>
    <x-chief::form.description>Dit e-mailadres geldt tevens als login.</x-chief::form.description>
    <x-chief::form.input.email id="email" name="email" value="{{ old('email', $user->email) }}" />
</x-chief::form.fieldset>

<x-chief::form.fieldset rule="roles">
    <x-chief::form.label for="roles" required>Rechten</x-chief::form.label>

    <x-chief::form.description>
        Geef aan met een of meerdere rollen welke rechten deze gebruiker ter beschikking heeft.
    </x-chief::form.description>

    <x-chief::multiselect
        id="roles"
        name="roles[]"
        :options="$roleNames"
        :selection="old('roles', $user->roleNames())"
        :multiple="true"
    />
</x-chief::form.fieldset>
