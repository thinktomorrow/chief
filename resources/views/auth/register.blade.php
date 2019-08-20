<!-- Login form area -->
@extends('chief::back._layouts.solo')

@section('title')
    Registreer
@endsection

@section('content')

    <div class="container min-h-screen flex items-center">
        <div class="row w-full justify-center my-32">

            <div class="xs-column-12 s-column-10 m-column-6 l-column-4 relative z-20">

                <span class="text-5xl font-bold leading-tight text-grey-500">Registreer</span>	

                <form id="valid" class="mt-8" role="form" method="POST" action="{{ route('register') }}">
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
                            <input class="inset-s" type="text" name="name" placeholder="Naam" id="name" value="" required autofocus>
                        </div>	
                        
                        <div class="stack">
                            <input class="inset-s" type="email" name="email" placeholder="E-mailadres" id="identity" value="" required>
                        </div>	
            
                        <div class="stack">
                            <input class="inset-s" type="password" name="password" placeholder="Wachtwoord" id="password" required>
                        </div>

                        <div class="stack">
                            <input class="inset-s" type="password" name="password_confirmation" placeholder="Wachtwoord herhalen" id="password_confirmation" required>
                        </div>
            
                        <button type="submit" class="btn btn-primary mb-16">Registreer</button>
            
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
