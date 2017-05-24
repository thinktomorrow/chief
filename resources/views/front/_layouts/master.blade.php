<!DOCTYPE html>
<!--[if lt IE 9]> <html class="lt-ie9"> <![endif]-->
<!--[if gte IE 9]><!--> <html lang="{{ app()->getLocale() }}"> <!--<![endif]-->
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, user-scalable=no" />

    <!-- search meta -->
    <title>@yield('pagetitle' ) {{ env('PROJECT_NAME') }}</title>
    <meta name="author" content="{{ env('PROJECT_NAME') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link href="{{ asset('assets/img/app-icon.png') }}" rel="apple-touch-icon" />
  </head>
  <body>
    @include('front._partials.metaheader')
     @yield('content')
     @stack('custom-scripts')
  </body>
</html>
