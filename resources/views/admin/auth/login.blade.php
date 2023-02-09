<x-chief::solo.template title="Welkom terug, Chief!">
    <form id="valid" role="form" method="POST" action="{{ route('chief.back.login.store') }}">
        {{ csrf_field() }}

        <div class="space-y-6">
            {{-- TODO: field errors are handled but still need to show error if login credentials are incorrect --}}
            @if($errors && count($errors) > 0)
                <x-chief::inline-notification type="error" size="medium" class="w-full">
                    @foreach ($errors->all() as $_error)
                        <p>{{ ucfirst($_error) }}</p>
                    @endforeach
                </x-chief::inline-notification>
            @endif

            <x-chief::input.group>
                <x-chief::input.label for="email" required>E-mailadres</x-chief::input.label>
                <x-chief::input.email id="email" name="email" value="{{ old('email') }}" placeholder="emailaddress@example.com" autofocus/>
            </x-chief::input.group>

            <x-chief::input.group>
                <x-chief::input.label for="password" required>Wachtwoord</x-chief::input.label>
                <x-chief::input.password id="password" name="password"/>
            </x-chief::input.group>

            <x-chief::input.group inner-class="flex items-start gap-2">
                <x-chief::input.checkbox id="rememberCheckbox" name="remember" :checked="old('remember')"/>
                <x-chief::input.label for="remember" class="body-dark" unset>
                    Ingelogd blijven
                </x-chief::input.label>
            </x-chief::input.group>

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
