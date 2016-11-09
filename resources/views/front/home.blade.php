<!DOCTYPE html>
<!--[if lt IE 9]><html class="lt-ie9 no-js"> <![endif]-->
<!--[if gte IE 9]><!--> <html class="no-js" lang="{{ app()->getLocale() }}"><!--<![endif]-->
<html>
    <head>
        <script>
            // If javascript is active, we can assign the 'js' class to our html root instead of 'no-js' class
            // Only use this class if Modernizr is not being used
            (function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);
        </script>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

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
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Skeleton awaits your command</div>
            </div>
        </div>
    </body>
</html>
