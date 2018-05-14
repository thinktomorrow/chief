<!-- Login form area -->
@extends('back._layouts.login')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <div class="login-page">
        <h1>Reset uw wachtwoord</h1>

        @if($errors and count($errors) > 0)
            <div class="message error">
                @foreach($errors->all() as $error)
                    <span class="lnr lnr-warning mr5"></span>{{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form class="login-form" role="form" method="POST" action="{{ route('back.password.request') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="input-group">
                <span class="lnr lnr-user"></span>
                {!! Form::text('email', null, array('class'=>'validate[required]', 'placeholder'=>'E-mail', 'id'=>'identity', 'value' => '$email')) !!}
            </div>

            <div class="input-group">
                <span class="lnr lnr-keyboard"></span>
                {!! Form::password('password', array('class'=>'validate[required]', 'placeholder'=>'Nieuw wachtwoord', 'id'=>'password')) !!}
            </div>

            <div class="input-group">
                <span class="lnr lnr-keyboard"></span>
                {!! Form::password('password-confirm', array('class'=>'validate[required]', 'name'=>'password_confirmation' , 'placeholder'=>'Herhaal wachtwoord', 'id'=>'password')) !!}
            </div>

            {!! Form::submit('Reset wachtwoord', array('class'=>'greyishBtn submitForm'))!!}

        </form>
    </div>
</div>
@endsection
