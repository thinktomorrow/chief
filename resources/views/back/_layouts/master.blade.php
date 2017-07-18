<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta name="google" content="notranslate" />
    <meta http-equiv="Content-Language" content="en_US" />
    <title>Chief admin</title>
    <meta name="author" content="Think Tomorrow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600'>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/back/theme/css/theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/back/theme/admin-tools/admin-forms/css/admin-forms.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/back/theme/vendor/plugins/magnific/magnific-popup.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/back/css/main.css') }}">
    @stack('custom-styles')

</head>

<body class="sb-o">

<div id="main">

    @include('back._modules.header')
    @include('back._modules.nav')

    <section id="content_wrapper">
        <header id="topbar">
            <div class="topbar-left">
                <h3>
                    @yield('page-title')
                    <div class="topbar-inside-right">
                        @yield('topbar-right')
                    </div>
                </h3>
            </div>
        </header>

        @include('back._elements.messages')
        @include('back._elements.errors')

        <section id="content">
            @yield('content')
        </section>

        @include('back._modules.footer')
    </section>
    @yield('sidebar')

</div>


<script src="{{ asset('assets/back/theme/vendor/jquery/jquery-1.11.1.min.js') }}"></script>
<script src="{{ asset('assets/back/theme/vendor/jquery/jquery_ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/back/theme/vendor/plugins/magnific/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/back/theme/js/utility/utility.js') }}"></script>
<script src="{{ asset('assets/back/theme/js/main.js') }}"></script>

<script src="{{ asset('assets/back/theme/vendor/plugins/fileupload/fileupload.js') }}"></script>
<script src="{{ asset('assets/back/js/main.js') }}"></script>

<script type="text/javascript">

jQuery(document).ready(function() {

        "use strict";

        // Init Theme Core
        Core.init();

    });
</script>

@stack('custom-scripts')

</body>

</html>
