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

        <link rel="preload" as="style" href="{{ Vite::buildAsset('resources/assets/css/main.css') }}" />
        <link rel="modulepreload" href="{{ Vite::buildAsset('resources/assets/js/main.js') }}" />
        <link rel="stylesheet" href="{{ Vite::buildAsset('resources/assets/css/main.css') }}" />
        <script type="module" src="{{ Vite::buildAsset('resources/assets/js/main.js') }}"></script>
    </head>

    <body class="bg-grey-50/50 min-h-screen">
        <main>
            {{ $slot }}
        </main>
    </body>
</html>
