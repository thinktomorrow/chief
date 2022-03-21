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
                <h1 class="text-center h1 display-dark">
                    @if($new_password)
                        Maak een wachtwoord aan
                    @else
                        Wijzig jouw wachtwoord
                    @endif
                </h1>

                <div class="card">
                    @if($errors and count($errors) > 0)
                        <x-chief-inline-notification type="error" size="large">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-chief-inline-notification>
                    @endif

                    <form
                        role="form"
                        method="POST"
                        action="{{ route('chief.back.password.update') }}"
                        class="prose prose-spacing prose-dark"
                    >
                        {{ csrf_field() }}

                        <input type="hidden" name="_method" value="PUT">

                        <div class="space-y-6">
                            <x-chief-form::formgroup id="password" label="Nieuw wachtwoord" required>
                                <input id="password" type="password" name="password">
                                <x-chief-form::formgroup.error error-ids="password"></x-chief-form::formgroup.error>
                            </x-chief-form::formgroup>

                            <x-chief-form::formgroup id="password_confirmation" label="Herhaal wachtwoord" required>
                                <input id="password_confirmation" type="password" name="password_confirmation">
                                <x-chief-form::formgroup.error error-ids="password_confirmation"></x-chief-form::formgroup.error>
                            </x-chief-form::formgroup>

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
                </div>
            </div>
        </div>
    </div>
@endsection
