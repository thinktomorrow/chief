@extends('back._layouts.login')

@section('title')
    Login
@endsection

@section('content')
<div class="login-page">
  <h1>Inloggen</h1>
  <p>"Don't let yesterday use up too much of today."</p>
    @if($errors and count($errors) > 0)
        <div class="message error">
            @foreach($errors->all() as $error)
                  <span class="lnr lnr-warning mr5"></span>{{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form class="login-form" id="valid" role="form" method="POST" action="{{ url('/login') }}">
      {{ csrf_field() }}
      <div class="input-group">
        <span class="lnr lnr-user"></span>
        {!! Form::text('email', null, array('class'=>'validate[required]', 'placeholder'=>'E-mail', 'id'=>'identity')) !!}
      </div>
      <div class="input-group">
        <span class="lnr lnr-keyboard"></span>
        {!! Form::password('password', array('class'=>'validate[required]', 'placeholder'=>'Wachtwoord', 'id'=>'password')) !!}
      </div>
      {!! Form::submit('Inloggen', array('class'=>'checker greyishBtn submitForm'))!!}

      <span class="message"><a href="{{ url('/password/reset') }}">Wachtwoord vergeten?</a></span>
    </form>
</div>
 @stop