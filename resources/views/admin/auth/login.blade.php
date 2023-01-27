<x-chief::solo.template title="Welkom terug, Chief!">
    <form id="valid" role="form" method="POST" action="{{ route('chief.back.login.store') }}">
        {{ csrf_field() }}

        <div class="space-y-6">
            {{-- TODO: field errors are handled but still need to show error if login credentials are incorrect --}}
            @if($errors && count($errors) > 0)
                <x-chief-inline-notification type="error" size="medium" class="w-full">
                    @foreach ($errors->all() as $_error)
                        <p>{{ ucfirst($_error) }}</p>
                    @endforeach
                </x-chief-inline-notification>
            @endif

            <x-chief-form::formgroup id="email" label="E-mailadres" required>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="emailaddress@example.com" autofocus>
            </x-chief-form::formgroup>

            <x-chief-form::formgroup id="password" label="Wachtwoord" required>
                <input id="password" name="password" type="password">
            </x-chief-form::formgroup>

            <x-chief-form::formgroup id="remember">
                <label for="rememberCheckbox" class="with-checkbox">
                    <input id="rememberCheckbox" name="remember" type="checkbox" {{ old('remember') ? 'checked=checked' : null  }}>
                    <span>Ingelogd blijven</span>
                </label>
            </x-chief-form::formgroup>

            <div class="space-y-3">
                <button type="submit" form="valid" class="flex justify-center w-full text-lg shadow-lg btn btn-primary">
                    Inloggen
                </button>

                <a
                    href="{{ route('chief.back.password.request') }}"
                    title="Wachtwoord vergeten"
                    class="flex justify-center w-full btn btn-grey"
                >
                    Wachtwoord vergeten?
                </a>
            </div>
        </div>
    </form>
</x-chief::solo.template>
