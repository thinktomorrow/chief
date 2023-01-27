@props([
    'title' => null
])

<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<!-- This project was proudly build by Think Tomorrow. More info at https://thinktomorrow.be -->
<head>
    <script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>

    @include('chief::templates.page._partials.metatags', ['title' => $title])
    @include('chief::templates.page._partials.favicon')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ chief_cached_asset('css/main.css') }}">
</head>

<body>
    <main>
        {{ $slot }}
    </main>
</body>
</html>
