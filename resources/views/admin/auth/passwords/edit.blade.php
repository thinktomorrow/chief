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
        <div class="row-center-center min-h-screen">
            <div class="w-full lg:w-1/2 2xl:w-1/3 window window-white space-y-6">
                <h1 class="text-grey-900">
                    @if($new_password)
                        Maak een wachtwoord aan
                    @else
                        Wijzig jouw wachtwoord
                    @endif
                </h1>

                @if($errors and count($errors) > 0)
                    <x-inline-notification type="error" size="large">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-inline-notification>
                @endif

                <form role="form" method="POST" action="{{ route('chief.back.password.update') }}" class="prose prose-dark">
                    {{ csrf_field() }}

                    <input type="hidden" name="_method" value="PUT">

                    <div class="space-y-6">
                        <div class="flex flex-col space-y-2">
                            <label for="password">Wachtwoord</label>
                            <input type="password" name="password" placeholder="Wachtwoord" id="password" value="{{ old('password') }}">
                        </div>

                        <div class="flex flex-col space-y-2">
                            <label for="password">Herhaal wachtwoord</label>
                            <input type="password" name="password_confirmation" placeholder="Herhaal wachtwoord" id="password-confirm">
                        </div>

                        <div class="space-x-4">
                            @if($new_password)
                                <button type="submit" class="btn btn-primary">Maak wachtwoord aan</button>
                            @else
                                <button type="submit" class="btn btn-primary">Wijzig wachtwoord</button>
                                <a href="{{ route('chief.back.dashboard') }}" class="btn btn-secondary">Annuleer</a>
                            @endif
                        </div>
                    </div>
                </form>
			</div>
        </div>
    </div>

@endsection
