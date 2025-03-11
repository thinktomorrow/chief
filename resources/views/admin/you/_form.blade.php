<div class="row-start-start gutter-3">
    <x-chief::input.group rule="firstname" class="w-full sm:w-1/2">
        <x-chief::form.label for="firstname" required>Voornaam</x-chief::form.label>
        <x-chief::input.text id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}" />
    </x-chief::input.group>

    <x-chief::input.group rule="lastname" class="w-full sm:w-1/2">
        <x-chief::form.label for="lastname" required>Achternaam</x-chief::form.label>
        <x-chief::input.text id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" />
    </x-chief::input.group>

    <x-chief::input.group rule="email" class="w-full">
        <x-chief::form.label for="email" required>Achternaam</x-chief::form.label>

        <x-chief::form.description>Dit e-mailadres geldt tevens als login.</x-chief::form.description>

        <x-chief::input.email id="email" name="email" value="{{ old('email', $user->email) }}" />
    </x-chief::input.group>

    <div class="w-full">
        <button form="updateForm" type="submit" class="btn btn-primary">Opslaan</button>
    </div>
</div>
