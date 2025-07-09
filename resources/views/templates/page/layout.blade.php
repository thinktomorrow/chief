@php
    use Thinktomorrow\Chief\Plugins\ChiefPluginSections;
@endphp

@props([
    'title' => null,
])

    <!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<!-- This project was proudly build by Think Tomorrow. More info at https://thinktomorrow.be -->
<head>
    <script>
        (function(html) {
            html.className = html.className.replace(/\bno-js\b/, 'js');
        })(document.documentElement);
    </script>

    @include('chief::templates.page._partials.metatags', ['title' => $title])
    @include('chief::templates.page._partials.favicon')
    @include('chief::templates.page._partials.fonts')

    {{-- Hide Alpine elements until Alpine is fully loaded --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <link rel="preload" as="style" href="{{ Vite::buildAsset('resources/assets/css/main.css') }}" />
    <link rel="modulepreload" href="{{ Vite::buildAsset('resources/assets/js/main.js') }}" />
    <link rel="stylesheet" href="{{ Vite::buildAsset('resources/assets/css/main.css') }}" />
    <script type="module" src="{{ Vite::buildAsset('resources/assets/js/main.js') }}"></script>

    @livewireStyles

    @stack('custom-styles')
</head>
<body class="bg-grey-50/50 min-h-screen">
<main>
    {{ $slot }}
    @stack('portals')
</main>

@include('chief::templates.page._partials.notifications')
@include('chief::templates.page._partials.refresh-modal')
@livewireScripts
@stack('custom-scripts')


@if(request()->input('site') && \Thinktomorrow\Chief\Sites\ChiefSites::verify(request()->input('site')))
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Trigger the admin scoped locale via query parameter e.g. ?site=nl
            window.dispatchEvent(new CustomEvent('scoped-to-locale', {
                detail: {
                    locale: "{{ request()->input('site') }}",
                },
            }));
        });
    </script>
@endif


@foreach (app(ChiefPluginSections::class)->getFooterSections() as $footerSection)
    @include($footerSection)
@endforeach
</body>
</html>
