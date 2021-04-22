<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="_token" content="{!! csrf_token() !!}"/>
        <link rel="stylesheet" href="{{ asset('chief-assets/back/css/main.css') }}">
        <title>Chief • @yield('title')</title>
    </head>

    <body>
        <main class="relative min-h-screen bg-gradient-to-br from-grey-100 to-grey-200">
            @yield('content')
        </main>
    </body>
</html>
