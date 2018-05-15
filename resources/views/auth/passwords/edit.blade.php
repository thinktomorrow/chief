@extends('back._layouts.master')

@section('title')
    Wijzig jouw wachtwoord
@endsection

@section('content')
    <div class="login-page">

        <div class="center-center" style="min-height:90vh;">

            <div class="stack">
                <h1>
                    @if($new_password)
                        Maak een wachtwoord aan
                    @else
                        Wijzig jouw wachtwoord
                    @endif
                </h1>

                @if($errors and count($errors) > 0)
                    <div class="message error">
                        @foreach($errors->all() as $error)
                            <span class="lnr lnr-warning mr5"></span>{{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form class="block login-form stack" role="form" method="POST" action="{{ route('back.password.update') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">

                    <div class="stack-s">
                        <input class="input inset-s" type="password" name="password" placeholder="wachtwoord" id="password" value="{{ old('password') }}">
                    </div>
                    <div class="stack-s">
                        <input class="input inset-s" type="password" name="password_confirmation" placeholder="herhaal wachtwoord" id="password-confirm">
                    </div>

                    @if($new_password)
                        <input class="btn btn-primary" type="submit" value="Maak wachtwoord aan">
                    @else
                        <input class="btn btn-primary" type="submit" value="Wijzig wachtwoord">
                    @endif


                </form>
            </div>

        </div>
    </div>
@endsection
