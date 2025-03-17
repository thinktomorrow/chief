{{-- Mobile navigation toggle --}}
<div class="container block lg:hidden">
    <div class="-ml-2 flex items-center justify-start pt-6 lg:hidden">
        <div
            data-mobile-navigation-toggle
            data-expand-navigation
            class="shrink-0 cursor-pointer rounded-md p-2 hover:bg-grey-200"
        >
            <x-chief::icon.menu class="size-6 text-grey-700" />
        </div>

        <span class="py-2 font-semibold text-grey-700">Menu</span>
    </div>
</div>

{{-- Navigation --}}
<div
    data-mobile-navigation
    class="fixed inset-0 hidden animate-slide-in-nav max-lg:z-10 max-lg:bg-white lg:static lg:block lg:animate-none"
>
    <div
        data-collapsible-navigation
        class="flex h-screen select-none flex-col justify-between gap-y-9 overflow-y-auto py-6 pl-4"
    >
        <div class="space-y-9">
            {{-- Desktop Chief title --}}
            <div class="hidden items-center justify-start lg:flex">
                <div data-toggle-navigation class="group shrink-0 cursor-pointer rounded-md p-2 hover:bg-grey-50">
                    <x-chief::icon.sidebar-left class="size-6 text-grey-500 group-hover:text-grey-900" />
                </div>

                <a
                    data-toggle-classes="hidden"
                    href="{{ route('chief.back.dashboard') }}"
                    title="Ga naar Dashboard"
                    class="{{ $isCollapsedOnPageLoad ? 'hidden' : null }} block w-full py-2 text-sm leading-6 text-grey-700"
                >
                    {{ config('app.client', 'Chief') }}
                </a>
            </div>

            {{-- Mobile Chief title --}}
            <div class="flex items-center justify-start lg:hidden">
                <div data-mobile-navigation-toggle class="shrink-0 cursor-pointer rounded-md p-2 hover:bg-grey-50">
                    <svg class="body-dark h-6 w-6"><use xlink:href="#icon-arrow-long-left"></use></svg>
                </div>

                <a
                    href="{{ route('chief.back.dashboard') }}"
                    title="Ga naar Dashboard"
                    class="body-dark inline-block px-3 py-2 font-medium"
                >
                    {{ config('app.client', 'Chief') }}
                </a>
            </div>

            <div>
                @include('chief::templates.page.nav.nav-project')
                @include('chief::templates.page.nav.nav-general')
                @include('chief::templates.page.nav.nav-settings')
            </div>
        </div>

        <div>
            @include('chief::templates.page.nav.nav-user')

            <p
                data-toggle-classes="hidden"
                @class([
                    'mt-3 px-2 text-xs text-grey-400',
                    'hidden' => $isCollapsedOnPageLoad,
                ])
            >
                Chief v{{ \Composer\InstalledVersions::getVersion('thinktomorrow/chief') }}
            </p>
        </div>
    </div>
</div>
