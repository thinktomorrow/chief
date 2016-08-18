<!DOCTYPE html>
<html>
<head>
    <title>Chief</title>

    <meta name="author" content="Think Tomorrow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/back/css/theme.css') }}">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 96px;
        }
    </style>

    @yield('custom-styles')

</head>
<body>

    <div class="container">
        <div class="content">

            @include('back._elements.messages')
            @include('back._elements.errors')

            @yield('content')

        </div>
    </div>

    @yield('custom-scripts')

</body>
</html>