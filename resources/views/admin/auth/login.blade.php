<x-chief::solo.template title="Welkom terug, Chief!">
    <form id="valid" role="form" method="POST" action="{{ route('chief.back.login.store') }}">
        {{ csrf_field() }}

        <div class="space-y-6">
            {{-- TODO: field errors are handled but still need to show error if login credentials are incorrect --}}
            @if ($errors && count($errors) > 0)
                <x-chief::inline-notification type="error" size="medium" class="w-full">
                    @foreach ($errors->all() as $_error)
                        <p>{{ ucfirst($_error) }}</p>
                    @endforeach
                </x-chief::inline-notification>
            @endif

            <x-chief::form.input.group>
                <x-chief::form.label for="email" required>E-mailadres</x-chief::form.label>
                <x-chief::form.input.email
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="emailaddress@example.com"
                    autofocus
                />
            </x-chief::form.input.group>

            <x-chief::form.input.group>
                <x-chief::form.label for="password" required>Wachtwoord</x-chief::form.label>
                <x-chief::form.input.password id="password" name="password" />
            </x-chief::form.input.group>

            <x-chief::form.input.group inner-class="flex items-start gap-2">
                <x-chief::form.input.checkbox id="remember" name="remember" :checked="old('remember')" />
                <x-chief::form.label for="remember" class="body-dark body leading-5" unset>
                    Ingelogd blijven
                </x-chief::form.label>
            </x-chief::form.input.group>

            <div class="space-y-3">
                <button type="submit" form="valid" class="btn btn-primary flex w-full justify-center text-lg shadow-lg">
                    Inloggen
                </button>

                <a
                    href="{{ route('chief.back.password.request') }}"
                    title="Wachtwoord vergeten"
                    class="btn btn-grey flex w-full justify-center"
                >
                    Wachtwoord vergeten?
                </a>
            </div>
        </div>
    </form>
</x-chief::solo.template>
