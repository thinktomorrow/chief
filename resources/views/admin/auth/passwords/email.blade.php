<x-chief::solo.template title="Je wachtwoord vergeten?">
    {{-- The status session value holds the passwords.sent message when an reset email has been succesfully requested. --}}
    @if(session('status'))
        <div>
            <p class="body body-dark">{{ session('status') }}</p>

            <div class="mt-4 space-x-4">
                <a
                    href="{{ route('chief.back.login') }}"
                    title="Terug naar login"
                    class="flex justify-center w-full btn btn-primary"
                > Terug naar login </a>
            </div>
        </div>
    @else
        <form id="valid" role="form" method="POST" action="{{ route('chief.back.password.email') }}">
            {{ csrf_field() }}

            <div class="space-y-6">
                {{-- TODO: mail confirmation message also shows as error --}}
                <x-chief-form::formgroup id="identity" label="E-mail" required>
                    <x-slot name="description">
                        Geef je e-mailadres in om je wachtwoord opnieuw in te stellen.
                    </x-slot>

                    <input id="identity" type="email" name="email" value="{{ old('email') }}">

                    <x-chief-form::formgroup.error error-ids="email"/>
                </x-chief-form::formgroup>

                <div class="space-y-3">
                    <button type="submit" class="flex justify-center w-full btn btn-primary">
                        Wachtwoord resetten
                    </button>

                    <a
                        href="{{ route('chief.back.login') }}"
                        title="Terug naar login"
                        class="flex justify-center w-full btn btn-grey"
                    > Terug naar login </a>
                </div>
            </div>
        </form>
    @endif
</x-chief::solo.template>
