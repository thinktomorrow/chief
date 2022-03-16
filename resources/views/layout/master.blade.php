<html>
    @include('chief::layout._partials.head')

    <body>
        @include('chief::layout._partials.healthbar')
        @include('chief::layout._partials.svg-symbols')

        <main id="main" class="min-h-screen bg-grey-50">
            <div class="flex flex-wrap items-start lg:flex-nowrap">
                <div class="relative top-0 z-50 w-full shrink-0 lg:sticky lg:w-auto">
                    <div class="container block lg:hidden">
                        <div class="flex items-center justify-start pt-6 -ml-2 lg:hidden">
                            <div
                                data-mobile-navigation-toggle
                                data-expand-navigation
                                class="p-2 rounded-lg cursor-pointer shrink-0 hover:bg-primary-50"
                            >
                                <svg class="w-6 h-6 text-grey-700"><use xlink:href="#menu"></use></svg>
                            </div>

                            <span class="px-3 py-2 link link-black"> Menu </span>
                        </div>
                    </div>

                    <div
                        data-mobile-navigation
                        class="fixed inset-0 hidden bg-white lg:static lg:block animate-slide-in-nav lg:animate-none"
                    >
                        @include('chief::layout.nav.nav')
                    </div>
                </div>

                <section role="sidebar">
                    @include('chief::manager.sidebar')
                </section>

                {{-- Content --}}
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
