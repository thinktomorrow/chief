<html>
    @include('chief::layout._partials.head')

    <body>
        @include('chief::layout.partials.healthbar')
        @include('chief::layout.partials.svg-symbols')

        <main id="main" class="bg-grey-150 min-h-screen">
            {{-- @include('chief::layout.nav.nav') --}}

            <div class="flex items-start">
                <aside class="sticky top-0 flex-shrink-0">
                    @include('chief::layout.nav.nav-new')
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

                    <!-- place outside the main content area as a place for modals, secondary forms, ... -->
                    @stack('portals')

                    {{-- @include('chief::back._layouts._partials.chief-footer') --}}
                    @yield('chief-footer')
                </div>
            </div>
        </main>

        @include('chief::layout._partials.foot')
    </body>
</html>
