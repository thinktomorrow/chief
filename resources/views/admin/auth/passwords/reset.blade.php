@extends('chief::layout.solo')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <div class="relative min-h-screen row-center-center">
        <div class="space-y-6 w-128">
            <h1 class="text-center text-black">Reset jouw wachtwoord</h1>

            <div class="card">
                <form role="form" method="POST" action="{{ route('chief.back.password.request') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="space-y-6">

                        <x-chief-form::formgroup id="identity" label="E-mail" required>
                            <input id="identity" type="email" name="email" value="{{ old('email') }}">
                            <x-chief-form::formgroup.error error-ids="email"></x-chief-form::formgroup.error>
                        </x-chief-form::formgroup>

                        <x-chief-form::formgroup id="password" label="Nieuw wachtwoord" required>
                            <input id="password" type="password" name="password">
                            <x-chief-form::formgroup.error error-ids="password"></x-chief-form::formgroup.error>
                        </x-chief-form::formgroup>

                        <x-chief-form::formgroup id="password_confirmation" label="Herhaal wachtwoord" required>
                            <input id="password_confirmation" type="password" name="password_confirmation">
                            <x-chief-form::formgroup.error error-ids="password_confirmation"></x-chief-form::formgroup.error>
                        </x-chief-form::formgroup>

                        <button type="submit" class="btn btn-primary">Reset mijn wachtwoord</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
