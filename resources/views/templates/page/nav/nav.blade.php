{{-- Mobile navigation toggle --}}
<div class="container max-w-full lg:hidden">
    <div class="-ml-2 flex items-center justify-start pt-6 lg:hidden">
        <div data-mobile-navigation-toggle class="hover:bg-grey-200 shrink-0 cursor-pointer rounded-md p-2">
            <x-chief::icon.menu class="text-grey-700 size-6" />
        </div>

        <span class="text-grey-700 py-2 font-semibold">Menu</span>
    </div>
</div>

{{-- Navigation --}}
<div
    data-mobile-navigation
    @class ([
        'fixed inset-0 hidden animate-slide-in-nav max-lg:z-10 max-lg:bg-white lg:static lg:flex lg:h-screen lg:animate-none',
        'select-none flex-col justify-between gap-y-9 overflow-y-auto py-6 pl-6 lg:sticky lg:top-0',
    ])
>
    <div class="space-y-6">
        {{-- Desktop Chief title --}}
        <div class="hidden items-center justify-start lg:flex">
            <div class="shrink-0 p-2">
                <x-chief::icon.quill-write class="text-grey-400 size-6" />
            </div>

            <a
                href="{{ route('chief.back.dashboard') }}"
                title="Ga naar Dashboard"
                class="text-grey-700 hover:text-grey-950 block w-full py-2 text-sm leading-6"
            >
                {{ config('app.client', 'Chief') }}
            </a>
        </div>

        {{-- Mobile Chief title --}}
        <div class="flex items-center justify-start lg:hidden">
            <div data-mobile-navigation-toggle class="hover:bg-grey-50 shrink-0 cursor-pointer rounded-md p-2">
                <x-chief::icon.arrow-left class="body-dark size-6" />
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
            @include ('chief::templates.page.nav.nav-project')
            @include ('chief::templates.page.nav.nav-general')
        </div>
    </div>

    <div>
        @include ('chief::templates.page.nav.nav-settings')
        @include ('chief::templates.page.nav.nav-user')

        <p class="text-grey-400 mt-3 px-2 text-xs">
            Je gebruikt momenteel Chief versie {{ \Composer\InstalledVersions::getVersion('thinktomorrow/chief') }}
        </p>
    </div>
</div>
