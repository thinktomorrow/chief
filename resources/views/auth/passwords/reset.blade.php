@extends('chief::back._layouts.solo')

@section('title')
    Reset wachtwoord
@endsection

@section('content')

    <div class="container min-h-screen flex items-center">
		<div class="row w-full justify-center my-32">
			<div class="xs-column-12 s-column-10 m-column-6 l-column-4 relative z-20">
                    
                <h1 class="mb-8">Reset uw wachtwoord</h1>

                <form class="block stack" role="form" method="POST" action="{{ route('chief.back.password.request') }}">

                    {{ csrf_field() }}

                    <input type="hidden" name="token" value="{{ $token }}">


                    <div class="stack">
                        <input type="email" class="inset-s" name="email" placeholder="E-mail" id="identity" value="{{ old('email') }}">
                    </div>

                    <div class="stack">
                        <input type="password" class="inset-s" name="password" placeholder="Nieuw wachtwoord" id="password">
                    </div>

                    <div class="stack">
                        <input type="password" class="inset-s" name="password_confirmation" placeholder="Herhaal wachtwoord" id="password-confirm">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Reset mijn wachtwoord</button>

                </form>

			</div>
        </div>
    </div>

@endsection
