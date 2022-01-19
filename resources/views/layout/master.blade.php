<html>
    @include('chief::layout._partials.head')

    <body>
        @include('chief::layout._partials.healthbar')
        @include('chief::layout._partials.svg-symbols')

        <main id="main" class="min-h-screen bg-grey-50 bg-gradient-to-r from-grey-50 to-grey-100">
            <div class="flex items-start">
                <aside class="sticky top-0 flex-shrink-0">
                    @include('chief::layout.nav.nav')
                </aside>

                <section role="sidebar">
                    @include('chief::manager.sidebar')
                </section>

                <section id="content" class="w-full py-12 space-y-8">
                    @yield('header')

                    <div v-cloak class="container v-loading">
                        <p class="text-grey-500">loading...</p>
                    </div>

                    <div v-cloak>
                        @include('chief::layout._partials.notifications')

                        @yield('content')
                    </div>
                </section>
            </div>

            <!-- place outside the main content area as a place for modals, secondary forms, ... -->
            @stack('portals')
        </main>

        @include('chief::layout._partials.foot')
    </body>
</html>
