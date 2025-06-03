<x-chief::solo.template title="Welkom terug, Chief!">
    <form id="valid" role="form" method="POST" action="{{ route('chief.back.login.store') }}">
        {{ csrf_field() }}

        <x-chief::form.fieldset rule="email">
            <x-chief::form.label for="email">E-mailadres</x-chief::form.label>
            <x-chief::form.input.email
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="emailaddress@example.com"
                autofocus
            />
        </x-chief::form.fieldset>

        <div data-slot="form-group" class="space-y-4">
            <x-chief::form.fieldset rule="password">
                <div data-slot="label" class="flex items-start justify-between gap-2">
                    <x-chief::form.label for="password">Wachtwoord</x-chief::form.label>

                    <x-chief::link
                        href="{{ route('chief.back.password.request') }}"
                        variant="blue"
                        size="sm"
                        tabindex="-1"
                        class="mt-0.5"
                    >
                        Wachtwoord vergeten?
                    </x-chief::link>
                </div>
                <x-chief::form.input.password id="password" name="password" />
            </x-chief::form.fieldset>

            <x-chief::form.fieldset class="flex items-start gap-2">
                <x-chief::form.input.checkbox id="remember" name="remember" :checked="old('remember')" />
                <x-chief::form.label for="remember" class="body-dark body leading-5" unset>
                    Ingelogd blijven
                </x-chief::form.label>
            </x-chief::form.fieldset>
        </div>

        <x-chief::button
            data-slot="form-group"
            type="submit"
            form="valid"
            size="lg"
            variant="blue"
            class="flex w-full justify-center"
        >
            Inloggen
        </x-chief::button>
    </form>
</x-chief::solo.template>
