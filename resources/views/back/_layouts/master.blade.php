<html>
    @include('chief::back._layouts._partials.head')

    <body>
        @include('chief::layout.partials.healthbar')
        @include('chief::layout.partials.svg-symbols')

        <main id="main" class="bg-grey-150 min-h-screen relative">
            @include('chief::layout.nav.nav')
            @yield('header')

            <section id="content" class="container relative pb-64">
                <div
                    v-cloak
                    class="v-loading inset-xl text-center absolute top-0 left-0 w-full h-full"
                    style="z-index: 99;"
                >
                    loading...
                </div>

                <div v-cloak>
                    @include('chief::layout.partials.notifications')
                    @include('chief::managers.sidebar')

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
