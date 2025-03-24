<x-chief::solo.template title="Reset jouw wachtwoord">
    <form role="form" method="POST" action="{{ route('chief.back.password.request') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}" />

        <div class="space-y-6">
            <x-chief::form.input.group rule="email">
                <x-chief::form.label for="email" required>E-mailadres</x-chief::form.label>
                <x-chief::form.input.email id="email" name="email" value="{{ old('email') }}" />
            </x-chief::form.input.group>

            <x-chief::form.input.group rule="password">
                <x-chief::form.label for="password" required>Nieuw wachtwoord</x-chief::form.label>
                <x-chief::form.input.password id="password" name="password" value="{{ old('password') }}" />
            </x-chief::form.input.group>

            <x-chief::form.input.group rule="password_confirmation">
                <x-chief::form.label for="password_confirmation" required>Herhaal wachtwoord</x-chief::form.label>
                <x-chief::form.input.password
                    id="password_confirmation"
                    name="password_confirmation"
                    value="{{ old('password_confirmation') }}"
                />
            </x-chief::form.input.group>

            <button type="submit" class="btn btn-primary flex w-full justify-center">Reset mijn wachtwoord</button>
        </div>
    </form>
</x-chief::solo.template>
