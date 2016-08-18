<!DOCTYPE html>
<html>

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>Chief admin</title>
    <meta name="author" content="Think Tomorrow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600'>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/theme/css/theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/theme/admin-tools/admin-forms/css/admin-forms.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/theme/vendor/plugins/magnific/magnific-popup.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/admin.css') }}">
    @yield('custom-styles')

</head>

<body>

<div id="main">

    @include('admin._modules.header')
    @include('admin._modules.nav')

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

        @include('admin._elements.messages')
        @include('admin._elements.errors')

        <section id="content">
            @yield('content')
        </section>

        @include('admin._modules.footer')
    </section>

</div>

<script src="{{ asset('assets/admin/theme/vendor/jquery/jquery-1.11.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/theme/vendor/jquery/jquery_ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/admin/theme/vendor/plugins/magnific/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/admin/theme/js/utility/utility.js') }}"></script>
<script src="{{ asset('assets/admin/theme/js/main.js') }}"></script>

<script src="{{ asset('assets/admin/vendor/fileupload/fileupload.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/sortable/sortable.js') }}"></script>
<script src="{{ asset('assets/admin/js/admin.js') }}"></script>

<script type="text/javascript">

jQuery(document).ready(function() {

        "use strict";

        // Init Theme Core
        Core.init();

    });
</script>

@yield('custom-scripts')

</body>

</html>