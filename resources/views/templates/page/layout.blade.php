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

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
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
<body class="min-h-screen bg-grey-50">
<main>
    {{ $slot }}

    @stack('portals')
</main>

@include('chief::templates.page._partials.symbols')
@include('chief::templates.page._partials.refresh-modal')

@livewireScripts

<script src="{{ chief_cached_asset('chief-assets/back/js/main.js') }}"></script>

@stack('custom-scripts')

{{-- Deprecated, should use custom-scripts instead --}}
@stack('custom-scripts-after-vue')

<script>

    document.addEventListener('livewire:initialized', () => {
        window.Livewire.hook('component.init', ({ component }) => {

            // Hack to prevent livewire from setting the url to the sidebar url (as used for forms and fragments)
            if (
                component.effects.path !== undefined &&
                (component.effects.path.includes('/fragment/') ||
                    component.effects.path.includes('/nestedfragment/') ||
                    component.effects.path.includes('/forms/'))
            ) {
                component.effects.path = undefined;
            }
        });
    });
</script>

@foreach (app(ChiefPluginSections::class)->getFooterSections() as $footerSection)
    @include($footerSection)
@endforeach
</body>
</html>
