<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="_token" content="{!! csrf_token() !!}"/>
    {!! Html::style('https://cdn.linearicons.com/free/1.0.0/icon-font.min.css')!!}
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <title>Chief • @yield('title')</title>
</head>
<body>
  <main>
    <aside>
      Login
    </aside>
    <section class="form">
      @yield('content')
    </section>
  </main>
  <footer>
    <p>&copy; {{ date('Y') }} &bull; <a href="mailto:support@chief.be">support@chief.be</a> </p>
  </footer>

    <script src="{{ asset('assets/js/jquery.js') }}" type="application/javascript"></script>

    @yield('admin.footerscripts')

</body>
</html>
