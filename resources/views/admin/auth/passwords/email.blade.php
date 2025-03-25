<x-chief::solo.template title="Je wachtwoord vergeten?">
    {{-- The status session value holds the passwords.sent message when an reset email has been succesfully requested. --}}

    @if (session('status'))
        <div class="space-y-4">
            <p class="body body-dark">{{ session('status') }}</p>

            <x-chief::button
                variant="blue"
                href="{{ route('chief.back.login') }}"
                title="Terug naar login"
                class="flex w-full justify-center"
            >
                Terug naar login
            </x-chief::button>
        </div>
    @else
        <form id="valid" role="form" method="POST" action="{{ route('chief.back.password.email') }}">
            {{ csrf_field() }}

            <x-chief::form.fieldset rule="email">
                <x-chief::form.label for="email" required>E-mailadres</x-chief::form.label>

                <x-chief::form.description>
                    Geef je e-mailadres in om je wachtwoord opnieuw in te stellen.
                </x-chief::form.description>

                <x-chief::form.input.email id="email" name="email" value="{{ old('email') }}" />
            </x-chief::form.fieldset>

            <div data-slot="form-group" class="space-y-3">
                <x-chief::button type="submit" size="lg" variant="blue" class="flex w-full justify-center">
                    Wachtwoord resetten
                </x-chief::button>

                <x-chief::button
                    href="{{ route('chief.back.login') }}"
                    title="Terug naar login"
                    size="lg"
                    class="flex w-full justify-center"
                >
                    Terug naar login
                </x-chief::button>
            </div>
        </form>
    @endif
</x-chief::solo.template>
