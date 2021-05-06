<html>
    @include('chief::layout._partials.head')

    <body>
        @include('chief::layout.partials.healthbar')
        @include('chief::layout.partials.svg-symbols')

        <main id="main" class="min-h-screen bg-grey-100">
            <div class="flex items-start">
                <aside class="sticky top-0 flex-shrink-0">
                    @include('chief::layout.nav.nav')
                </aside>

                <div class="w-full py-12 space-y-12">
                    @yield('header')

                    <section id="content">
                        <div v-cloak class="container v-loading">
                            <p class="text-grey-500">loading...</p>
                        </div>

                        <div v-cloak>
                            @include('chief::layout.partials.notifications')
                            @include('chief::manager.sidebar')

                            @yield('content')
                        </div>
                    </section>
                </div>
            </div>

            <!-- place outside the main content area as a place for modals, secondary forms, ... -->
            @stack('portals')
        </main>

        @include('chief::layout._partials.foot')
    </body>
</html>
