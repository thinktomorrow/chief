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
    <link rel="icon" href="{{ asset('chief-assets/back/img/favicon.png')}}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- hide vue elements until vue is loaded -->
    <style type="text/css">
        .v-loading { display: none !important; }
        [v-cloak].v-loading { display: block !important; }
        [v-cloak] { display: none; }
    </style>

    <link rel="stylesheet" type="text/css" href="{{ chief_cached_asset('/chief-assets/back/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/back/css/vendor/slim.min.css') }}">

    <style type="text/css">
        .slim { max-height: 250px; }
        .slim-error { min-height: 60px; }
        .slim-upload-status { padding: .3em; }
        .slim .slim-area .slim-upload-status[data-state=error] {
            right: .5em;
            left: .5em;
            line-height: 1.1;
            padding: .3em;
            white-space: normal;
        }
        .slim .slim-area .slim-result img {
            height: 100%;
            object-fit: cover;
        }
        .thumb [data-state=empty] { height: 80px; }
    </style>

    @stack('custom-styles')
</head>
