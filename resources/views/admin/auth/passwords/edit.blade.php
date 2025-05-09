@php
    $title = $new_password ? 'Maak een wachtwoord aan' : 'Wijzig jouw wachtwoord';
@endphp

<x-chief::solo.template :title="$title">
    <form role="form" method="POST" action="{{ route('chief.back.password.update') }}">
        {{ csrf_field() }}

        <input type="hidden" name="_method" value="PUT" />

        @if ($errors and count($errors) > 0)
            <x-chief::callout data-slot="form-group" variant="red" title="Oops, er klopt iets niet">
                <x-slot name="icon">
                    <x-chief::icon.solid.alert />
                </x-slot>

                @foreach ($errors->all() as $_error)
                    <p>{{ ucfirst($_error) }}</p>
                @endforeach
            </x-chief::callout>
        @endif

        <x-chief::form.fieldset>
            <x-chief::form.label for="password" required>Nieuw wachtwoord</x-chief::form.label>
            <x-chief::form.input.password id="password" name="password" />
        </x-chief::form.fieldset>

        <x-chief::form.fieldset>
            <x-chief::form.label for="password_confirmation" required>Herhaal wachtwoord</x-chief::form.label>
            <x-chief::form.input.password id="password_confirmation" name="password_confirmation" />
        </x-chief::form.fieldset>

        <div data-slot="form-group" class="space-y-3">
            @if ($new_password)
                <x-chief::button type="submit" variant="blue" size="lg" class="flex w-full justify-center">
                    Wachtwoord aanmaken
                </x-chief::button>
            @else
                <x-chief::button type="submit" variant="blue" size="lg" class="flex w-full justify-center">
                    Wachtwoord wijzigen
                </x-chief::button>

                <x-chief::button
                    href="{{ route('chief.back.dashboard') }}"
                    title="Annuleren"
                    size="lg"
                    class="flex w-full justify-center"
                >
                    Annuleren
                </x-chief::button>
            @endif
        </div>
    </form>
</x-chief::solo.template>
