<!DOCTYPE html>
<html>

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>Hura admin</title>
    <meta name="author" content="Think Tomorrow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600'>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/back/theme/css/theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/back/theme/admin-tools/admin-forms/css/admin-forms.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/back/theme/vendor/plugins/magnific/magnific-popup.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/back/css/main.css') }}">

    @yield('custom-styles')

</head>
<body>
  @include('back._elements.messages')
  @include('back._elements.errors')
    <section id="content">
        <div class="col-sm-6 center-block">
          <div class="admin-form theme-default theme-info">
            <div class="panel heading-border panel-warning bg-light">

            <div class="panel-heading">
                          <span class="panel-title">
                            <img src="{{ asset('assets/img/chief.jpg')}}" class="col-sm-3" alt="logo" data-pin-nopin="true">
                          </span>
                      </div>
            @yield('content')
          </div>
          </div>
        </div>
    </section>

    @stack('custom-scripts')

</body>
</html>
