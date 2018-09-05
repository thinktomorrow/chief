@extends('chief::back._layouts.login')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <div class="reset-wrapper">
        <div class="reset-block">

        <h1>Reset uw wachtwoord</h1>

        @if($errors and count($errors) > 0)
            <div class="message error">
                @foreach($errors->all() as $error)
                    <span class="lnr lnr-warning mr5"></span>{{ $error }}<br>
                @endforeach
            </div>
        @endif


        <form class="login-form" role="form" method="POST" action="{{ route('chief.back.password.request') }}">
                    <div class="form-wrapper">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="squished">
                <div class="input-group-prefix relative">
                    <span class="input-prefix"><span class="lnr lnr-envelope"></span></span>
                    <input type="email" class="validate[required]" name="email" placeholder="E-mail" id="identity" value="{{ old('email') }}">
                </div>
            </div>

            <div class="squished">
                <div class="input-group-prefix relative">
                    <span class="input-prefix"><span class="lnr lnr-keyboard"></span></span>
                    <input type="password" class="validate[required]" name="password" placeholder="Nieuw wachtwoord" id="password">
                </div>
            </div>

            <div class="squished">
                <div class="input-group-prefix relative">
                    <span class="input-prefix"><span class="lnr lnr-keyboard"></span></span>
                    <input type="password" class="validate[required]" name="password_confirmation" placeholder="Herhaal wachtwoord" id="password-confirm">
                </div>
            </div>

            <div class="squished">
                <button type="submit" class="btn btn-block submitForm">Reset mijn wachtwoord</button>
            </div>
        </div>

        </form>
    </div>
</div>
@endsection
