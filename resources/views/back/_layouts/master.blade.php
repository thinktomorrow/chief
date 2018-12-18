@include('chief::back._layouts._partials.head')

<body>

<main id="main" class="bg-grey-lightest min-h-screen">
    @include('chief::back._layouts._partials.nav')
    @yield('header')

    <section id="content" class="container relative">
        <div v-cloak class="v-loading inset-xl text-center bg-grey-lightest" style="position: absolute; top: 0;left: 0;z-index: 99;width: 100%;height: 100%;">loading...</div>
        <div v-cloak class="stack">
            @include('chief::back._elements.errors')
            @include('chief::back._elements.messages')
            @yield('content')
        </div>
    </section>

    <!-- place outside the main content area as a place for modals, secondary forms, ... -->
    @stack('portals')

</main>

@include('chief::back._layouts._partials.foot')
