@formgroup
    @slot('label', 'Voornaam')
    @slot('isRequired', true)

    <div class="space-y-2">
        <input id="firstName" type="text" name="firstname" value="{{ old('firstname',$user->firstname) }}" class="w-full">

        @error('firstname')
            <x-inline-notification type="error">
                {{ $message }}
            </x-inline-notification>
        @enderror
    </div>
@endformgroup

@formgroup
    @slot('label', 'Achternaam')
    @slot('isRequired', true)

    <div class="space-y-2">
        <input id="lastName" type="text" name="lastname" value="{{ old('lastname',$user->lastname) }}" class="w-full">

        @error('lastname')
            <x-inline-notification type="error">
                {{ $message }}
            </x-inline-notification>
        @enderror
    </div>
@endformgroup

@formgroup
    @slot('label', 'E-mail')
    @slot('description', 'Dit e-mailadres geldt tevens als jouw login.')
    @slot('isRequired', true)

    <div class="space-y-2">
        <input id="email" type="email" name="email" value="{{ old('email',$user->email) }}" class="w-full">

        @error('email')
            <x-inline-notification type="error">
                {{ $message }}
            </x-inline-notification>
        @enderror
    </div>
@endformgroup
