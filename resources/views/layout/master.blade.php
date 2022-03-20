<html>
    @include('chief::layout._partials.head')

    <body>
        @include('chief::layout._partials.svg-symbols')

        <main id="main" class="min-h-screen bg-grey-50">
            <div class="flex flex-wrap items-start lg:flex-nowrap">
                <section class="relative top-0 z-10 w-full shrink-0 lg:sticky lg:w-auto">
                    @include('chief::layout.nav.nav')
                </section>

                <section role="sidebar">
                    @include('chief::manager.sidebar')
                </section>

                {{-- Content --}}
                <section id="content" class="w-full">
                    @include('chief::layout._partials.healthbar')

                    <div class="py-12 space-y-8">
                        @yield('header')

                        <div v-cloak class="container v-loading">
                            <!-- loading -->
                        </div>

                        <div v-cloak>
                            @include('chief::layout._partials.notifications')

                            @yield('content')
                        </div>
                    </div>
                </section>
            </div>

            <!-- place outside the main content area as a place for modals, secondary forms, ... -->
            @stack('portals')
        </main>

        @include('chief::layout._partials.foot')
    </body>
</html>
