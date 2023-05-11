<div class="row-start-start gutter-3">
    <x-chief::input.group rule="firstname" class="w-full sm:w-1/2">
        <x-chief::input.label for="firstname" required>Voornaam</x-chief::input.label>
        <x-chief::input.text id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}"/>
    </x-chief::input.group>

    <x-chief::input.group rule="lastname" class="w-full sm:w-1/2">
        <x-chief::input.label for="lastname" required>Achternaam</x-chief::input.label>
        <x-chief::input.text id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}"/>
    </x-chief::input.group>

    <x-chief::input.group rule="email" class="w-full">
        <x-chief::input.label for="email" required>
            Achternaam
        </x-chief::input.label>

        <x-chief::input.description>
            Dit e-mailadres geldt tevens als login.
        </x-chief::input.description>

        <x-chief::input.email id="email" name="email" value="{{ old('email', $user->email) }}"/>
    </x-chief::input.group>

    <div class="w-full">
        <button form="updateForm" type="submit" class="btn btn-primary"> Opslaan </button>
    </div>
</div>
