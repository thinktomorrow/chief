<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="_token" content="{!! csrf_token() !!}"/>
		<link href="https://fonts.googleapis.com/css?family=Magra:400,700&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
		<link rel="stylesheet" href="{{ asset('chief-assets/back/css/main.css') }}">
		<title>Chief • @yield('title')</title>
	</head>

	<body>

		<main class="relative min-h-screen">

			@yield('content')

			<footer class="absolute bottom-0 left-0 right-0">
				<div class="flex justify-end">
					<img class="h-32" src="{{ asset('chief-assets/back/img/chief-totem.png') }}">
				</div>
				<div class="bg-tertiary-500 h-12 flex items-center justify-center">
					<p>&copy; {{ date('Y') }} &bull; <a href="mailto:chief@thinktomorrow.be">chief@thinktomorrow.be</a> &bull; <a href="https://getchief.be" target="_blank"> Chief SMS.</a> </p>
				</div>
			</footer>

		</main>

		@yield('admin.footerscripts')

	</body>
</html>
