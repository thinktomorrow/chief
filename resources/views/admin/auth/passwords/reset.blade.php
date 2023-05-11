<x-chief::solo.template title="Reset jouw wachtwoord">
    <form role="form" method="POST" action="{{ route('chief.back.password.request') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="space-y-6">
            <x-chief::input.group rule="email">
                <x-chief::input.label for="email" required>E-mailadres</x-chief::input.label>
                <x-chief::input.email id="email" name="email" value="{{ old('email') }}"/>
            </x-chief::input.group>

            <x-chief::input.group rule="password">
                <x-chief::input.label for="password" required>Nieuw wachtwoord</x-chief::input.label>
                <x-chief::input.password id="password" name="password" value="{{ old('password') }}"/>
            </x-chief::input.group>

            <x-chief::input.group rule="password_confirmation">
                <x-chief::input.label for="password_confirmation" required>Herhaal wachtwoord</x-chief::input.label>
                <x-chief::input.password id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}"/>
            </x-chief::input.group>

            <button type="submit" class="flex justify-center w-full btn btn-primary">Reset mijn wachtwoord</button>
        </div>
    </form>
</x-chief::solo.template>
