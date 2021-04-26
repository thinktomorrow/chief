@extends('chief::layout.solo')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <div class="relative row-center-center min-h-screen">
        <div class="w-full lg:w-1/2 2xl:w-1/3 window window-white window-lg space-y-8">
            <h1 class="text-grey-900">Reset jouw wachtwoord</h1>

            <form role="form" method="POST" action="{{ route('chief.back.password.request') }}" class="mb-0">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-6">
                    <x-chief-formgroup label="E-mail" id="identity" name="email">
                        <input id="identity" name="email" type="email" placeholder="E-mail" value="{{ old('email') }}">
                    </x-chief-formgroup>

                    <x-chief-formgroup label="Nieuw wachtwoord" id="password" name="password">
                        <input type="password" id="password" name="password" placeholder="Nieuw wachtwoord">
                    </x-chief-formgroup>

                    <x-chief-formgroup label="Herhaal wachtwoord" id="password_confirmation" name="password_confirmation">
                        <input type="password" id="password-confirm" name="password_confirmation" placeholder="Herhaal wachtwoord">
                    </x-chief-formgroup>

                    <button type="submit" class="btn btn-primary">Reset mijn wachtwoord</button>
                </div>
            </form>
        </div>
    </div>
@endsection
