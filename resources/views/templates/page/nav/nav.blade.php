{{-- Mobile navigation toggle --}}
<div class="container block lg:hidden">
    <div class="flex items-center justify-start pt-6 -ml-2 lg:hidden">
        <div
            data-mobile-navigation-toggle
            data-expand-navigation
            class="p-2 rounded-md cursor-pointer shrink-0 hover:bg-grey-200"
        >
            <svg class="w-6 h-6 text-grey-700"><use xlink:href="#icon-bars-4"></use></svg>
        </div>

        <span class="py-2 font-semibold text-grey-700"> Menu </span>
    </div>
</div>

{{-- Navigation --}}
<div
    data-mobile-navigation
    class="fixed inset-0 hidden bg-grey-50/90 lg:static lg:block animate-slide-in-nav lg:animate-none"
>
    <div
        data-collapsible-navigation
        class="flex flex-col justify-between h-screen px-3 py-6 overflow-y-auto border-r select-none border-grey-200 gap-y-9"
    >
        <div class="space-y-9">
            {{-- Desktop Chief title --}}
            <div class="items-center justify-start hidden gap-1 lg:flex">
                <div data-toggle-navigation class="p-2 rounded-md cursor-pointer shrink-0 hover:bg-grey-50 group">
                    <svg
                        data-toggle-classes="hidden"
                        class="{{ $isCollapsedOnPageLoad ? 'hidden' : null }} w-6 h-6 text-grey-500 group-hover:text-grey-900"
                    >
                        <use xlink:href="#icon-arrows-pointing-in"></use>
                    </svg>

                    <svg
                        data-toggle-classes="!block"
                        class="{{ $isCollapsedOnPageLoad ? '!block' : null }} hidden w-6 h-6 text-grey-500 group-hover:text-grey-900"
                    >
                        <use xlink:href="#icon-arrows-pointing-out"></use>
                    </svg>
                </div>

                <a
                    data-toggle-classes="hidden"
                    href="{{ route('chief.back.dashboard') }}"
                    title="Ga naar Dashboard"
                    class="block w-full py-2 font-medium text-grey-700 text-sm leading-6 {{ $isCollapsedOnPageLoad ? 'hidden' : null }}"
                > {{ config('app.client', 'Chief') }} </a>
            </div>

            {{-- Mobile Chief title --}}
            <div class="flex items-center justify-start lg:hidden">
                <div data-mobile-navigation-toggle class="p-2 rounded-md cursor-pointer shrink-0 hover:bg-grey-50">
                    <svg class="w-6 h-6 body-dark"><use xlink:href="#icon-arrow-long-left"></use></svg>
                </div>

                <a
                    href="{{ route('chief.back.dashboard') }}"
                    title="Ga naar Dashboard"
                    class="inline-block px-3 py-2 font-medium body-dark"
                > {{ config('app.client', 'Chief') }} </a>
            </div>

            <div>
                @include('chief::templates.page.nav.nav-project')
                @include('chief::templates.page.nav.nav-general')
                @include('chief::templates.page.nav.nav-settings')
            </div>
        </div>

        <div>
            @include('chief::templates.page.nav.nav-user')

            <p data-toggle-classes="hidden" @class([
                'px-3 mt-3 text-xs text-grey-400',
                'hidden' => $isCollapsedOnPageLoad
            ])>
                Chief v{{ \Composer\InstalledVersions::getVersion('thinktomorrow/chief') }}
            </p>
        </div>
    </div>
</div>
