@extends('chief::back._layouts.solo')

@section('title')
    Reset wachtwoord
@endsection

@section('content')

    <div class="container min-h-screen flex items-center">
		<div class="row w-full justify-center my-32">
			<div class="xs-column-12 s-column-10 m-column-6 l-column-4 relative z-20">
                    
                <h1 class="mb-8">Je wachtwoord vergeten?</h1>
                <p>Geef je e-mailadres in om je wachtwoord te opnieuw in te stellen.</p>

                @if(session('status'))
                    <div>{{ session('status') }}</div>
                @else

                    <form id="valid" class="block stack" role="form" method="POST" action="{{ route('chief.back.password.email') }}">

                        {{ csrf_field() }}

                        @if ($errors->has('email'))
                            <div class="label label-error">{{ $errors->first('email') }}</div>
                        @endif

                        <div class="stack">
                            <input type="email" class="inset-s" name="email" placeholder="E-mail" id="identity" value="{{ old('email') }}">
                        </div>
                        
                        <button type="submit" class="btn btn-primary mr-4">Reset mijn wachtwoord</button>
                        <a href="{{ route('chief.back.login') }}">Keer terug naar de login</a>

                    </form>

                @endif

			</div>
        </div>
    </div>

@endsection
