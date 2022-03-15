@extends('chief::layout.solo')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <div class="relative min-h-screen row-center-center">
        <div class="space-y-6 w-128">
            <h1 class="text-center text-black">Reset jouw wachtwoord</h1>

            <x-chief-forms::window>
                <form role="form" method="POST" action="{{ route('chief.back.password.request') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="space-y-6">
                        <x-chief::field.form label="E-mail" id="identity" error="email">
                            <input id="identity" name="email" type="email" placeholder="E-mail" value="{{ old('email') }}">
                        </x-chief::field.form>

                        <x-chief::field.form label="Nieuw wachtwoord" id="password" error="password">
                            <input type="password" id="password" name="password" placeholder="Nieuw wachtwoord">
                        </x-chief::field.form>

                        <x-chief::field.form label="Herhaal wachtwoord" id="password_confirmation" error="password_confirmation">
                            <input type="password" id="password-confirm" name="password_confirmation" placeholder="Herhaal wachtwoord">
                        </x-chief::field.form>

                        <button type="submit" class="btn btn-primary">Reset mijn wachtwoord</button>
                    </div>
                </form>
            </x-chief-forms::window>
        </div>
    </div>
@endsection
