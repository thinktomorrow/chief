@extends('chief::layout.solo')

@section('title')
    Reset wachtwoord
@endsection

@section('content')
    <div class="relative row-center-center min-h-screen">
        <div class="w-full lg:w-1/2 2xl:w-1/3 window window-white window-lg space-y-8">
            <h1 class="text-grey-900">Je wachtwoord vergeten?</h1>

            {{-- TODO: is this still being used? --}}
            @if(session('status'))
                <div>{{ session('status') }}</div>
            @else
                <form id="valid" role="form" method="POST" action="{{ route('chief.back.password.email') }}">
                    {{ csrf_field() }}

                    <div class="space-y-6">
                        {{-- TODO: mail confirmation message also shows as error --}}
                        <x-chief::field error="email">
                            <x-slot name="description">
                                <p>Geef je e-mailadres in om je wachtwoord te opnieuw in te stellen.</p>
                            </x-slot>

                            <input id="identity" name="email" type="email" placeholder="E-mail" value="{{ old('email') }}">
                        </x-chief::field>

                        <div class="space-x-4">
                            <button type="submit" class="btn btn-primary">Reset mijn wachtwoord</button>

                            <a href="{{ route('chief.back.login') }}" class="btn btn-primary-outline">Terug naar login</a>
                        </div>
                    </div>
                </form>

            @endif
        </div>
    </div>
@endsection
