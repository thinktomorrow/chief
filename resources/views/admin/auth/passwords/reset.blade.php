@extends('chief::layout.solo')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <div class="relative min-h-screen row-center-center">
        <div class="space-y-6 w-128">
            <h1 class="text-center text-black">Reset jouw wachtwoord</h1>

            <div class="window">
                <form role="form" method="POST" action="{{ route('chief.back.password.request') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="space-y-6">

                        <x-chief-forms::formgroup.wrapper id="identity" label="E-mail" required>
                            <input id="identity" type="email" name="email" value="{{ old('email') }}">
                            <x-chief-forms::formgroup.error error-ids="email"></x-chief-forms::formgroup.error>
                        </x-chief-forms::formgroup.wrapper>

                        <x-chief-forms::formgroup.wrapper id="password" label="Nieuw wachtwoord" required>
                            <input id="password" type="password" name="password">
                            <x-chief-forms::formgroup.error error-ids="password"></x-chief-forms::formgroup.error>
                        </x-chief-forms::formgroup.wrapper>

                        <x-chief-forms::formgroup.wrapper id="password_confirmation" label="Herhaal wachtwoord" required>
                            <input id="password_confirmation" type="password" name="password_confirmation">
                            <x-chief-forms::formgroup.error error-ids="password_confirmation"></x-chief-forms::formgroup.error>
                        </x-chief-forms::formgroup.wrapper>

                        <button type="submit" class="btn btn-primary">Reset mijn wachtwoord</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
