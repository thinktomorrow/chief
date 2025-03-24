<x-chief::solo.template title="Je wachtwoord vergeten?">
    {{-- The status session value holds the passwords.sent message when an reset email has been succesfully requested. --}}

    @if (session('status'))
        <div>
            <p class="body body-dark">{{ session('status') }}</p>

            <div class="mt-4 space-x-4">
                <a
                    href="{{ route('chief.back.login') }}"
                    title="Terug naar login"
                    class="btn btn-primary flex w-full justify-center"
                >
                    Terug naar login
                </a>
            </div>
        </div>
    @else
        <form id="valid" role="form" method="POST" action="{{ route('chief.back.password.email') }}">
            {{ csrf_field() }}

            <div class="space-y-6">
                <x-chief::form.input.group rule="email">
                    <x-chief::form.label for="email" required>E-mailadres</x-chief::form.label>

                    <x-chief::form.description>
                        Geef je e-mailadres in om je wachtwoord opnieuw in te stellen.
                    </x-chief::form.description>

                    <x-chief::form.input.email id="email" name="email" value="{{ old('email') }}" />
                </x-chief::form.input.group>

                <div class="space-y-3">
                    <button type="submit" class="btn btn-primary flex w-full justify-center">
                        Wachtwoord resetten
                    </button>

                    <a
                        href="{{ route('chief.back.login') }}"
                        title="Terug naar login"
                        class="btn btn-grey flex w-full justify-center"
                    >
                        Terug naar login
                    </a>
                </div>
            </div>
        </form>
    @endif
</x-chief::solo.template>
