{{-- Mobile navigation toggle --}}
<div class="container block lg:hidden">
    <div class="flex items-center justify-start pt-6 -ml-2 lg:hidden">
        <div
            data-mobile-navigation-toggle
            data-expand-navigation
            class="p-2 rounded-lg cursor-pointer shrink-0 hover:bg-grey-200"
        >
            <svg class="w-6 h-6 text-grey-800"><use xlink:href="#icon-bars-4"></use></svg>
        </div>

        <span class="px-3 py-2 font-medium text-grey-800"> Menu </span>
    </div>
</div>

{{-- Navigation --}}
<div
    data-mobile-navigation
    class="fixed inset-0 hidden bg-white lg:static lg:block animate-slide-in-nav lg:animate-none lg:shadow-card"
>
    <div
        data-collapsible-navigation
        class="flex flex-col justify-between h-screen px-3 overflow-y-auto border-r divide-y select-none divide-grey-100 border-grey-100"
    >
        <div class="py-6 divide-y divide-grey-100">
            <div class="pb-6">
                {{-- Desktop Chief title --}}
                <div class="items-center justify-start hidden lg:flex">
                    <div data-toggle-navigation class="p-2 rounded-lg cursor-pointer shrink-0 hover:bg-grey-50">
                        <svg data-toggle-classes="hidden" class="{{ $isCollapsedOnPageLoad ? 'hidden' : null }} w-6 h-6 text-grey-800">
                            <use xlink:href="#icon-arrows-pointing-in"></use>
                        </svg>

                        <svg data-toggle-classes="!block" class="{{ $isCollapsedOnPageLoad ? '!block' : null }} hidden w-6 h-6 text-grey-800">
                            <use xlink:href="#icon-arrows-pointing-out"></use>
                        </svg>
                    </div>

                    <a
                        data-toggle-classes="hidden"
                        href="{{ route('chief.back.dashboard') }}"
                        title="Ga naar Dashboard"
                        class="block w-full px-3 py-2 font-medium text-grey-800 {{ $isCollapsedOnPageLoad ? 'hidden' : null }}"
                    > {{ config('app.client', 'Chief') }} </a>
                </div>

                {{-- Mobile Chief title --}}
                <div class="flex items-center justify-start lg:hidden">
                    <div data-mobile-navigation-toggle class="p-2 rounded-lg cursor-pointer shrink-0 hover:bg-grey-50">
                        <svg class="w-6 h-6 text-grey-800"><use xlink:href="#icon-arrow-long-left"></use></svg>
                    </div>

                    <a
                        href="{{ route('chief.back.dashboard') }}"
                        title="Ga naar Dashboard"
                        class="inline-block px-3 py-2 font-medium text-grey-800"
                    > {{ config('app.client', 'Chief') }} </a>
                </div>
            </div>

            <div class="pt-6">
                @include('chief::template.nav.nav-project')
                @include('chief::template.nav.nav-general')
                @include('chief::template.nav.nav-settings')
            </div>
        </div>

        <div class="py-6">
            @include('chief::template.nav.nav-user')
        </div>
    </div>
</div>
