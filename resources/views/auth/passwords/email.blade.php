<!-- Login form area -->
@extends('back._layouts.login')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <div class="reset-wrapper">
        <div class="reset-block">
            <h1>Je wachtwoord vergeten?</h1>
            <p>Geef je e-mailadres in om je wachtwoord te opnieuw in te stellen.</p>
            @if (session('status'))
                <div class="message succes">
                    <span class="lnr lnr-checkmark-circle"></span>
                    {{ session('status') }}
                </div>
            @else
                <form id="valid" role="form" method="POST" action="{{ route('password.email') }}">
                    {{ csrf_field() }}
                    <div class="form-wrapper">
                        <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                            @if ($errors->has('email'))
                                <div class="message error squished">
                                    <span class="lnr lnr-warning lnr-margin"></span> {{ $errors->first('email') }}
                                </div>
                            @endif
                            <div class="squished">
                                <div class="input-group-prefix relative">
                                    <span class="input-prefix"><span class="lnr lnr-envelope"></span></span>
                                    <input type="email" class="validate[required]" name="email" placeholder="E-mail" id="identity" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="squished">
                        <button type="submit" class="btn btn-block submitForm">Reset mijn wachtwoord</button>
                    </div>
                </form>
            @endif

            <div class="back-btn">
                <a href="{{ route('back.login') }}">Keer terug naar de login</a>
            </div>

        </div>
    </div>
@stop
