<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta name="google" content="notranslate" />
    <meta http-equiv="Content-Language" content="nl-BE" />
    <title>Chief • @yield('page-title', 'Admin')</title>
    <meta name="author" content="Think Tomorrow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('chief-assets/back/img/favicon.ico')}}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- hide vue elements until vue is loaded -->
    <style type="text/css">[v-cloak]{ display:none; }</style>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ cached_asset('chief-assets/back/css/vendors/redactor.css', 'back') }}" />
    <link rel="stylesheet" type="text/css" href="{{ cached_asset('/chief-assets/back/css/main.css','back') }}">

    @stack('custom-styles')
</head>
