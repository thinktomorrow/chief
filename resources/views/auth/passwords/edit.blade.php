@extends('chief::back._layouts.login')

@section('title')
    Wijzig jouw wachtwoord
@endsection

@section('content')

    <div class="container min-h-screen flex items-center">
		<div class="row w-full justify-center my-32">
			<div class="xs-column-12 s-column-10 m-column-6 l-column-4">

                <h1 class="mb-8">
                    @if($new_password)
                        Maak een wachtwoord aan
                    @else
                        Wijzig jouw wachtwoord
                    @endif
                </h1>

                @if($errors and count($errors) > 0)
                    <div class="label label-error stack">
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form class="block stack" role="form" method="POST" action="{{ route('chief.back.password.update') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">

                    <div class="stack">
                        <input class="inset-s" type="password" name="password" placeholder="wachtwoord" id="password" value="{{ old('password') }}">
                    </div>
                    <div class="stack">
                        <input class="inset-s" type="password" name="password_confirmation" placeholder="herhaal wachtwoord" id="password-confirm">
                    </div>

                    @if($new_password)
						<button type="submit" class="btn btn-primary">Maak wachtwoord aan</button>
                    @else
						<button type="submit" class="btn btn-primary mr-4">Wijzig wachtwoord</button>
                        <a href="{{ url()->previous() }}">Annuleer</a>
                    @endif


                </form>
			</div>
        </div>
    </div>

@endsection
