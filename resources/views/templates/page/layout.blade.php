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

        {{-- Hide Alpine elements until Alpine is fully loaded --}}
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        <link rel="stylesheet" href="{{ chief_cached_asset('chief-assets/back/css/main.css') }}" />

        @livewireStyles

        @stack('custom-styles')
    </head>
    <body class="min-h-screen bg-grey-50/50">
        <main>
            {{ $slot }}

            @stack('portals')
        </main>

        @include('chief::templates.page._partials.notifications')
        @include('chief::templates.page._partials.refresh-modal')

        @livewireScripts

        <script src="{{ chief_cached_asset('chief-assets/back/js/main.js') }}"></script>

        @stack('custom-scripts')

        <script>
            document.addEventListener('livewire:initialized', () => {
                    // Trigger the admin scoped locale via query parameter e.g. ?site=nl
                    @if(request()->input('site') && \Thinktomorrow\Chief\Sites\ChiefSites::verify(request()->input('site')))
                    window.dispatchEvent(new CustomEvent('scoped-to-locale', { detail: { locale: '{{ request()->input('site') }}' } }));
                    @endif
                });
        </script>

        @foreach (app(ChiefPluginSections::class)->getFooterSections() as $footerSection)
            @include($footerSection)
        @endforeach
    </body>
</html>
