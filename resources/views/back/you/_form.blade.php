
@chiefformgroup(['field' => ['firstname', 'lastname']])
    @slot('label', 'Naam')
    <div class="row gutter">
        <div class="column-5">
            <label for="firstName">Voornaam</label>
            <input id="firstName" class="input inset-s" type="text" name="firstname" value="{{ old('firstname',$user->firstname) }}">
        </div>
        <div class="column-7">
            <label for="lastName">Achternaam</label>
            <input id="lastName" class="input inset-s" type="text" name="lastname" value="{{ old('lastname',$user->lastname) }}">
        </div>
    </div>
@endchiefformgroup

@chiefformgroup(['field' => 'email'])
    @slot('label', 'E-mail')
    @slot('description', 'Dit e-mail adres geldt tevens als jouw login.')
    <label for="email">E-mail</label>
    <input id="email" class="input inset-s" type="email" name="email" value="{{ old('email',$user->email) }}">
@endchiefformgroup
