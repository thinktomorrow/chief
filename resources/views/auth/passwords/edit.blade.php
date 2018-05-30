@extends('chief::back._layouts.solo')

@section('title')
    Wijzig jouw wachtwoord
@endsection

@section('content')

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
                        <span class="lnr lnr-warning mr5"></span> {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form class="block login-form stack" role="form" method="POST" action="{{ route('chief.back.password.update') }}">
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
                    <input class="btn btn-primary inline-s" type="submit" value="Wijzig wachtwoord">
                    <a href="{{ url()->previous() }}">Annuleer</a>
                @endif


            </form>
        </div>

@endsection
