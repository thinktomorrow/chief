@extends('chief::back._layouts.solo')

@section('title')
    Log in
@endsection

@section('content')
    <div class="container min-h-screen flex items-center justify-center">
        <div class="row w-full justify-center">
            <div class="xs-column-12 s-column-10 m-column-6 l-column-4 z-20">
                @include('chief::back._layouts._partials.logo')

                <span class="text-4xl font-bold leading-tight text-grey-500">Welkom terug, Chief!</span>

                <form id="valid" class="mt-8" role="form" method="POST" action="{{ route('chief.back.login.store') }}">
                    {{ csrf_field() }}
                    <div>
                        @if($errors and count($errors) > 0)
                            <div class="label label-warning stack">
                                @foreach($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </div>
                        @endif

                        <div class="stack">
                            <input class="inset-s" type="email" name="email" placeholder="E-mail" id="identity" value="{{ old('email') }}" autofocus>
                        </div>

                        <div class="stack">
                            <input class="inset-s" type="password" name="password" placeholder="Wachtwoord" id="password">
                        </div>

                        <div class="stack">
                            <label for="rememberCheckbox" class="flex items-center">
                                <input id="rememberCheckbox" class="mr-2" {{ old('remember') ? 'checked=checked':null  }} type="checkbox" name="remember">
                                <span>Hou me ingelogd</span>
                            </label>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary mr-4">Inloggen</button>
                            <a href="{{ route('chief.back.password.request') }}">Wachtwoord vergeten?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
