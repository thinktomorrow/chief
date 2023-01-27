<x-chief::solo.template title="Reset jouw wachtwoord">
    <form role="form" method="POST" action="{{ route('chief.back.password.request') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="space-y-6">
            <x-chief-form::formgroup id="identity" label="E-mail" required>
                <input id="identity" type="email" name="email" value="{{ old('email') }}">
                <x-chief-form::formgroup.error error-ids="email"/>
            </x-chief-form::formgroup>

            <x-chief-form::formgroup id="password" label="Nieuw wachtwoord" required>
                <input id="password" type="password" name="password">
                <x-chief-form::formgroup.error error-ids="password"/>
            </x-chief-form::formgroup>

            <x-chief-form::formgroup id="password_confirmation" label="Herhaal wachtwoord" required>
                <input id="password_confirmation" type="password" name="password_confirmation">
                <x-chief-form::formgroup.error error-ids="password_confirmation"/>
            </x-chief-form::formgroup>

            <button type="submit" class="flex justify-center w-full btn btn-primary">Reset mijn wachtwoord</button>
        </div>
    </form>
</x-chief::solo.template>
