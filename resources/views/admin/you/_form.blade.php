@formgroup
    @slot('label', 'Voornaam')
    @slot('isRequired', true)

    <input id="firstName" type="text" name="firstname" value="{{ old('firstname',$user->firstname) }}" class="w-full">
@endformgroup

@formgroup
    @slot('label', 'Achternaam')
    @slot('isRequired', true)

    <input id="lastName" type="text" name="lastname" value="{{ old('lastname',$user->lastname) }}" class="w-full">
@endformgroup

@formgroup
    @slot('label', 'E-mail')
    @slot('description', 'Dit e-mail adres geldt tevens als jouw login.')
    @slot('isRequired', true)

    <input id="email" type="email" name="email" value="{{ old('email',$user->email) }}" class="w-full">
@endformgroup
