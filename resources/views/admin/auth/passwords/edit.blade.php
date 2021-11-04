@extends('chief::layout.solo')

@section('title')
    @if($new_password)
        Maak een wachtwoord aan
    @else
        Wijzig jouw wachtwoord
    @endif
@endsection

@section('content')
    <div class="container">
        <div class="min-h-screen row-center-center">
            <div class="space-y-6 w-128">
                <h1 class="text-center text-black">
                    @if($new_password)
                        Maak een wachtwoord aan
                    @else
                        Wijzig jouw wachtwoord
                    @endif
                </h1>

                <x-chief::window>
                    @if($errors and count($errors) > 0)
                        <x-chief-inline-notification type="error" size="large">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-chief-inline-notification>
                    @endif

                    <form role="form" method="POST" action="{{ route('chief.back.password.update') }}" class="prose prose-dark">
                        {{ csrf_field() }}

                        <input type="hidden" name="_method" value="PUT">

                        <div class="space-y-6">
                            <x-chief::field.form label="Wachtwoord" id="password" error="password">
                                <input type="password" name="password" placeholder="Wachtwoord" id="password" value="{{ old('password') }}">
                            </x-chief::field.form>

                            <x-chief::field.form label="Herhaal wachtwoord" id="password-confirm" error="password_confirmation">
                                <input type="password" name="password_confirmation" placeholder="Herhaal wachtwoord" id="password-confirm">
                            </x-chief::field.form>

                            <div class="space-x-2">
                                @if($new_password)
                                    <button type="submit" class="btn btn-primary">Maak wachtwoord aan</button>
                                @else
                                    <button type="submit" class="btn btn-primary">Wijzig wachtwoord</button>

                                    <a
                                        href="{{ route('chief.back.dashboard') }}"
                                        title="Annuleren"
                                        class="btn btn-primary-outline"
                                    > Annuleren </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </x-chief::window>
            </div>
        </div>
    </div>
@endsection
