<html>
    @include('chief::back._layouts._partials.head')

    <body>
        @include('chief::layout.partials.healthbar')
        @include('chief::layout.partials.svg-symbols')

        <main id="main" class="relative bg-grey-150 min-h-screen">
            @include('chief::layout.nav.nav')

            @yield('header')

            <section id="content">
                <div v-cloak class="v-loading">
                    loading...
                </div>

                <div v-cloak>
                    @include('chief::layout.partials.notifications')
                    @include('chief::manager.sidebar')

                    @yield('content')
                </div>
            </section>

            <!-- place outside the main content area as a place for modals, secondary forms, ... -->
            @stack('portals')
            @yield('chief-footer')
        </main>

        @include('chief::back._layouts._partials.foot')
    </body>
</html>
