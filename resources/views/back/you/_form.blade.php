@formgroup(['field' => 'firstname'])
    @slot('label', 'Voornaam')
    @slot('isRequired', $field->required())
    <div class="row gutter">
        <div class="column-12">
            <label for="firstName">Voornaam</label>
            <input id="firstName" class="input inset-s" type="text" name="firstname" value="{{ old('firstname',$user->firstname) }}">
        </div>
    </div>
@endformgroup

@formgroup(['field' => 'lastname'])
    @slot('label', 'Achternaam')
    @slot('isRequired', $field->required())
    <div class="row gutter">
        <div class="column-12">
            <label for="lastName">Achternaam</label>
            <input id="lastName" class="input inset-s" type="text" name="lastname" value="{{ old('lastname',$user->lastname) }}">
        </div>
    </div>
@endformgroup

@formgroup(['field' => 'email'])
    @slot('label', 'E-mail')
    @slot('description', 'Dit e-mail adres geldt tevens als jouw login.')
    @slot('isRequired', $field->required())
    <label for="email">E-mail</label>
    <input id="email" class="input inset-s" type="email" name="email" value="{{ old('email',$user->email) }}">
@endformgroup
