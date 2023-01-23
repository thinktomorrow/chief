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

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- hide vue elements until vue is loaded -->
    <style type="text/css">
        .v-loading { display: none !important; }
        [v-cloak].v-loading { display: block !important; }
        [v-cloak] { display: none !important; }
    </style>

    @include('chief::layout._partials.favicon')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ chief_cached_asset('/chief-assets/back/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('/assets/back/css/vendor/slim.min.css') }}">

    <livewire:styles />

    @stack('custom-styles')
</head>
