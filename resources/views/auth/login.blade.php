@extends('chief::back._layouts.solo')

@section('title')
  	Login
@endsection

@section('content')

	<div class="container min-h-screen flex items-center">
		<div class="row w-full justify-center my-32">

			<div class="xs-column-12 s-column-10 m-column-6 l-column-4 relative z-20">

				<span class="text-5xl font-bold leading-tight text-grey-500">Welcome back, Chief!</span>	

				<form id="valid" class="mt-8" role="form" method="POST" action="{{ route('chief.back.login.store') }}">
					{{ csrf_field() }}
					<div>
			
						@if($errors and count($errors) > 0)
							<div class="label label-error stack">
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
			
						<button type="submit" class="btn btn-primary mb-16">Inloggen</button>
			
						<div class="message">
							<a href="{{ route('chief.back.password.request') }}">Wachtwoord vergeten?</a>
						</div>
			
					</div>
				</form>
			</div>
		</div>
	</div>

@stop
