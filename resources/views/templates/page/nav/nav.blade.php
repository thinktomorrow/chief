{{-- Mobile navigation toggle --}}
<div class="container max-w-full lg:hidden">
    <div class="-ml-2 flex items-center justify-start pt-6 lg:hidden">
        <div data-mobile-navigation-toggle class="shrink-0 cursor-pointer rounded-md p-2 hover:bg-grey-200">
            <x-chief::icon.menu class="size-6 text-grey-700" />
        </div>

        <span class="py-2 font-semibold text-grey-700">Menu</span>
    </div>
</div>

{{-- Navigation --}}
<div
    data-mobile-navigation
    @class([
        'fixed inset-0 hidden animate-slide-in-nav max-lg:z-10 max-lg:bg-white lg:static lg:flex lg:min-h-screen lg:animate-none',
        'select-none flex-col justify-between gap-y-9 py-6 pl-6',
    ])
>
    <div class="space-y-9">
        {{-- Desktop Chief title --}}
        <div class="hidden items-center justify-start lg:flex">
            <div class="shrink-0 p-2">
                <x-chief::icon.quill-write class="size-6 text-grey-400" />
            </div>

            <a
                href="{{ route('chief.back.dashboard') }}"
                title="Ga naar Dashboard"
                class="block w-full py-2 text-sm leading-6 text-grey-700 hover:text-grey-950"
            >
                {{ config('app.client', 'Chief') }}
            </a>
        </div>

        {{-- Mobile Chief title --}}
        <div class="flex items-center justify-start lg:hidden">
            <div data-mobile-navigation-toggle class="shrink-0 cursor-pointer rounded-md p-2 hover:bg-grey-50">
                <svg class="body-dark h-6 w-6">
                    <use xlink:href="#icon-arrow-long-left"></use>
                </svg>
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
        </div>
    </div>

    <div>
        @include('chief::templates.page.nav.nav-settings')
        @include('chief::templates.page.nav.nav-user')

        <p class="mt-3 px-2 text-xs text-grey-400">
            Je gebruikt momenteel Chief versie {{ \Composer\InstalledVersions::getVersion('thinktomorrow/chief') }}
        </p>
    </div>
</div>
