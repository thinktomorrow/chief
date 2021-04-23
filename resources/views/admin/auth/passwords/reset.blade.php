@extends('chief::layout.solo')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <div class="relative row-center-center min-h-screen">
        <div class="window window-white window-lg max-w-lg space-y-6 prose prose-dark">
            <h1>Reset jouw wachtwoord</h1>

            <form role="form" method="POST" action="{{ route('chief.back.password.request') }}" class="mb-0">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-6">
                    @formgroup
                        @slot('label', 'E-mail')

                        <div class="space-y-2">
                            <input type="email" name="email" placeholder="E-mail" id="identity" value="{{ old('email') }}" class="w-full">
                            @error('email')
                                <x-inline-notification type="error">
                                    {{ $message }}
                                </x-inline-notification>
                            @enderror
                        </div>
                    @endformgroup

                    @formgroup
                        @slot('label', 'Nieuw wachtwoord')

                        <div class="space-y-2">
                            <input type="password" name="password" placeholder="Nieuw wachtwoord" id="password" class="w-full">
                            @error('password')
                                <x-inline-notification type="error">
                                    {{ $message }}
                                </x-inline-notification>
                            @enderror
                        </div>
                    @endformgroup

                    @formgroup
                        @slot('label', 'Herhaal wachtwoord')

                        <div class="space-y-2">
                            <input type="password" name="password_confirmation" placeholder="Herhaal wachtwoord" id="password-confirm" class="w-full">
                            @error('password_confirmation')
                                <x-inline-notification type="error">
                                    {{ $message }}
                                </x-inline-notification>
                            @enderror
                        </div>
                    @endformgroup

                    <button type="submit" class="btn btn-primary">Reset mijn wachtwoord</button>
                </div>
            </form>
        </div>
    </div>
@endsection
