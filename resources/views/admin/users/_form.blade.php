<div class="row-start-start gutter-3">
    <x-chief::input.group rule="firstname" class="w-full lg:w-1/2">
        <x-chief::form.label for="firstname" required>Voornaam</x-chief::form.label>
        <x-chief::input.text id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}" />
    </x-chief::input.group>

    <x-chief::input.group rule="lastname" class="w-full lg:w-1/2">
        <x-chief::form.label for="lastname" required>Achternaam</x-chief::form.label>
        <x-chief::input.text id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" />
    </x-chief::input.group>

    <x-chief::input.group rule="email" class="w-full">
        <x-chief::form.label for="email" required>E-mailadres</x-chief::form.label>

        <x-chief::form.description>Dit e-mailadres geldt tevens als login.</x-chief::form.description>

        <x-chief::input.email id="email" name="email" value="{{ old('email', $user->email) }}" />
    </x-chief::input.group>

    <x-chief::input.group rule="roles" class="w-full">
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
    </x-chief::input.group>
</div>
