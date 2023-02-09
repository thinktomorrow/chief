@php
    $title = $new_password ? 'Maak een wachtwoord aan' : 'Wijzig jouw wachtwoord';
@endphp

<x-chief::solo.template :title="$title">
    <form role="form" method="POST" action="{{ route('chief.back.password.update') }}">
        {{ csrf_field() }}

        <input type="hidden" name="_method" value="PUT">

        <div class="space-y-6">
            @if($errors and count($errors) > 0)
                <x-chief::inline-notification type="error" size="large">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-chief::inline-notification>
            @endif

            <x-chief::input.group>
                <x-chief::input.label for="password" required>Nieuw wachtwoord</x-chief::input.label>
                <x-chief::input.password id="password" name="password" />
            </x-chief::input.group>

            <x-chief::input.group>
                <x-chief::input.label for="password_confirmation" required>Herhaal wachtwoord</x-chief::input.label>
                <x-chief::input.password id="password_confirmation" name="password_confirmation" />
            </x-chief::input.group>

            <div class="space-y-3">
                @if($new_password)
                    <button type="submit" class="flex justify-center w-full btn btn-primary">Wachtwoord aanmaken</button>
                @else
                    <button type="submit" class="flex justify-center w-full btn btn-primary">Wachtwoord wijzigen</button>

                    <a
                        href="{{ route('chief.back.dashboard') }}"
                        title="Annuleren"
                        class="flex justify-center w-full btn btn-grey"
                    > Annuleren </a>
                @endif
            </div>
        </div>
    </form>
</x-chief::solo.template>
