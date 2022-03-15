@extends('chief::layout.solo')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <div class="relative min-h-screen row-center-center">
        <div class="space-y-6 w-128">
            <h1 class="text-center text-black">Je wachtwoord vergeten?</h1>

            <div class="window">
                {{-- The status session value holds the passwords.sent message when an reset email has been succesfully requested. --}}
                @if(session('status'))
                    <div>
                        <p>{{ session('status') }}</p>
                        <div class="space-x-4 mt-4">
                            <a
                                    href="{{ route('chief.back.login') }}"
                                    title="Terug naar login"
                                    class="btn btn-primary-outline"
                            > Terug naar login </a>
                        </div>
                    </div>
                @else
                    <form id="valid" role="form" method="POST" action="{{ route('chief.back.password.email') }}">
                        {{ csrf_field() }}

                        <div class="space-y-6">
                            {{-- TODO: mail confirmation message also shows as error --}}

                            <x-chief-form::formgroup id="identity" label="E-mail" required>

                                <x-slot name="description">
                                    <p>Geef je e-mailadres in om je wachtwoord opnieuw in te stellen.</p>
                                </x-slot>

                                <input id="identity" type="email" name="email" value="{{ old('email') }}">
                                <x-chief-form::formgroup.error error-ids="email"></x-chief-form::formgroup.error>
                            </x-chief-form::formgroup>

                            <div class="space-x-4">
                                <button type="submit" class="btn btn-primary">Reset mijn wachtwoord</button>

                                <a
                                    href="{{ route('chief.back.login') }}"
                                    title="Terug naar login"
                                    class="btn btn-primary-outline"
                                > Terug naar login </a>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
