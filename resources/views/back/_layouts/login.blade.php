<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="{{ asset('chief-assets/back/css/login.css') }}">
    <title>Chief • @yield('title')</title>
</head>
<body>
  <main>
    <aside></aside>
    <section class="form">
      @yield('content')
    </section>
  </main>
  <footer>
    <p>&copy; {{ date('Y') }} &bull; <a href="mailto:chief@thinktomorrow.be">chief@thinktomorrow.be</a> </p>
  </footer>

    @yield('admin.footerscripts')

</body>
</html>
