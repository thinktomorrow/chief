<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Squanto translations admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .section-divider{
            margin: 2em 0;
            text-transform: uppercase;
            color: #ddd;
        }

        .tab-content{
            padding:1em;
        }

        .subtle{
            font-size:.9em;
            color:#dddddd;
        }
    </style>
    @yield('custom-styles')
</head>

<body>

<div id="main">

    <div class="container">
        <header>
            <h1>
                @yield('page-title')
                <div class="pull-right">
                    @yield('topbar-right')
                </div>
            </h1>
        </header>

        @include('squanto::_defaults.errors')
        @include('squanto::_defaults.messages')

        <section id="content">
            @yield('content')
        </section>
    </div>

    @include('squanto::_defaults.footer')

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@yield('custom-scripts')

</body>
</html>