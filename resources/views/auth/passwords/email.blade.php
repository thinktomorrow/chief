<!-- Login form area -->
@extends('back._layouts.login')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <!-- GIVE IN YOUR EMAIL ADRESS TO RESET YOUR PWD -->
    <div class="login-page">
      <h1>Je wachtwoord vergeten?</h1>
      <p>Geef e-mailadres in om je wachtwoord te opnieuw in te stellen</p>
      <div class="form">
            @if (session('status'))
              <div class="message succes">
                  <span class="lnr lnr-checkmark-circle"></span>
                  {{ session('status') }}
              </div>
            @else
            <form id="valid" role="form" method="POST" action="{{ url('/password/email') }}">
                {{ csrf_field() }}
                    <div class="{{ $errors->has('email') ? ' has-error' : '' }}">
                      @if ($errors->has('email'))
                        <div class="message error">
                          <span class="lnr lnr-warning"></span> {{ $errors->first('email') }}
                        </div>
                      @endif
                      <div class="input-group">
                        <span class="lnr lnr-envelope"></span>
                        {!! Form::text('email', null, array('class'=>'validate[required]', 'placeholder'=>'Uw e-mail', 'id'=>'identity')) !!}
                      </div>
                    </div>
                    {!! Form::submit('Reset mijn wachtwoord', array('class'=>'checker greyishBtn submitForm'))!!}
                    <span class="message"><a href="{{ url('/login') }}">Terug naar login</a></span>

            </form>
            @endif
        </div>
    </div>
@stop