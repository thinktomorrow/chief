@extends('chief::layout.solo')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <div class="relative row-center-center min-h-screen">
        <div class="window window-white window-lg max-w-lg space-y-6 prose prose-dark">
            <h1>Je wachtwoord vergeten?</h1>

            <p>Geef je e-mailadres in om je wachtwoord te opnieuw in te stellen.</p>

            {{-- TODO: is this still being used? --}}
            @if(session('status'))
                <div>{{ session('status') }}</div>
            @else
                <form id="valid" role="form" method="POST" action="{{ route('chief.back.password.email') }}">
                    {{ csrf_field() }}

                    <div class="space-y-6">
                        @formgroup
                            <div class="space-y-2">
                                <input type="email" class="w-full" name="email" placeholder="E-mail" id="identity" value="{{ old('email') }}">

                                {{-- TODO: mail confirmation message also shows as error --}}
                                @error('email')
                                    <x-inline-notification type="error">
                                        {{ $message }}
                                    </x-inline-notification>
                                @enderror
                            </div>
                        @endformgroup

                        <div class="space-x-4">
                            <button type="submit" class="btn btn-primary">Reset mijn wachtwoord</button>

                            <a href="{{ route('chief.back.login') }}" class="btn btn-secondary">Terug naar login</a>
                        </div>
                    </div>
                </form>

            @endif
        </div>
    </div>
@endsection
