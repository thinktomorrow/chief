{{-- Mobile navigation toggle --}}
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

{{-- Navigation --}}
<div
    data-mobile-navigation
    class="fixed inset-0 hidden bg-white lg:static lg:block animate-slide-in-nav lg:animate-none lg:shadow-card"
>
    <div
        data-collapsible-navigation
        class="flex flex-col justify-between h-screen px-3 overflow-auto border-r divide-y select-none divide-grey-100 border-grey-100"
    >
        <div class="py-6">
            {{-- Desktop Chief title --}}
            <div class="items-center justify-start hidden lg:flex">
                <div data-toggle-navigation class="p-2 rounded-lg cursor-pointer shrink-0 hover:bg-primary-50">
                    <svg class="w-6 h-6 text-black"><use xlink:href="#menu"></use></svg>
                </div>

                <a
                    data-toggle-classes="hidden"
                    href="{{ route('chief.back.dashboard') }}"
                    title="Ga naar Dashboard"
                    class="block w-full px-3 py-2 font-semibold text-black {{ $isCollapsedOnPageLoad ? 'hidden' : null }}"
                > {{ config('app.client', 'Chief') }} </a>
            </div>

            {{-- Mobile Chief title --}}
            <div class="flex items-center justify-start lg:hidden">
                <div data-mobile-navigation-toggle class="p-2 rounded-lg cursor-pointer shrink-0 hover:bg-primary-50">
                    <svg class="w-6 h-6 text-black"><use xlink:href="#icon-arrow-rtl"></use></svg>
                </div>

                <a
                    href="{{ route('chief.back.dashboard') }}"
                    title="Ga naar Dashboard"
                    class="inline-block px-3 py-2 font-semibold text-black"
                > {{ config('app.client', 'Chief') }} </a>
            </div>

            @include('chief::layout.nav.nav-project')
            @include('chief::layout.nav.nav-general')
            @include('chief::layout.nav.nav-settings')
        </div>

        <div class="py-6">
            @include('chief::layout.nav.nav-user')
        </div>
    </div>
</div>
