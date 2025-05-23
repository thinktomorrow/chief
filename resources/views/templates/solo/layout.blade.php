@props([
    'title' => null,
])

<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
    <!-- This project was proudly build by Think Tomorrow. More info at https://thinktomorrow.be -->
    <head>
        <script>
            (function (html) {
                html.className = html.className.replace(/\bno-js\b/, 'js');
            })(document.documentElement);
        </script>

        @include('chief::templates.page._partials.metatags', ['title' => $title])
        @include('chief::templates.page._partials.favicon')

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,100..900;1,100..900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
            rel="stylesheet"
        />

        <link rel="stylesheet" href="{{ chief_cached_asset('css/main.css') }}" />
    </head>

    <body class="min-h-screen bg-grey-50/50">
        <main>
            {{ $slot }}
        </main>
    </body>
</html>
