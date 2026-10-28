{{--
    PROTOTYPE (wayfinder ticket 08): candidate for a docs page template.
    Same shell as chief::page.template, but the Chief nav column is replaced by the $nav slot.
--}}
@props([
    'title' => null,
    'nav' => null,
    'footer' => null,
])

<x-chief::page.layout :title="$title">
    <div class="flex max-lg:flex-col lg:min-h-screen lg:items-stretch">
        {{-- Docs navigation, mirroring chief::templates.page.nav.nav --}}
        <div class="pt-docs-sidebar w-full shrink-0 lg:w-64">
            <div class="container max-w-full lg:hidden">
                <div class="-ml-2 flex items-center justify-start pt-6 lg:hidden">
                    <div data-mobile-navigation-toggle class="hover:bg-grey-200 shrink-0 cursor-pointer rounded-md p-2">
                        <x-chief::icon.menu class="text-grey-700 size-6" />
                    </div>
                    <span class="text-grey-700 py-2 font-semibold">Handleiding</span>
                </div>
            </div>

            <div
                data-mobile-navigation
                @class([
                    'fixed inset-0 hidden animate-slide-in-nav max-lg:z-10 max-lg:bg-white lg:static lg:flex lg:h-screen lg:animate-none',
                    'select-none flex-col justify-between gap-y-9 overflow-y-auto py-6 pl-6 lg:sticky lg:top-0',
                ])
            >
                <div class="space-y-6">
                    <div class="flex items-center justify-start lg:hidden">
                        <div data-mobile-navigation-toggle class="hover:bg-grey-50 shrink-0 cursor-pointer rounded-md p-2 lg:hidden">
                            <x-chief::icon.arrow-left class="body-dark size-6" />
                        </div>
                    </div>

                    <div>
                    <x-chief::nav.item
                        label="Terug naar {{ config('app.client', 'Chief') }}"
                        url="{{ route('chief.back.dashboard') }}"
                        icon='<svg viewBox="0 0 24 24" fill="none"><path d="M4 12H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M8.5 7.5L4 12L8.5 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                    />
                    </div>


                    <div>{{ $nav }}</div>
                </div>

                <div>
                    <p class="text-grey-400 px-2 text-xs">
                        Handleiding bij Chief versie {{ \Composer\InstalledVersions::getVersion('thinktomorrow/chief') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- DO NOT DELETE min-w-0 (cfr. chief::page.template) --}}
        <main class="pt-docs-main min-w-0 grow">
            {{ $slot }}
        </main>
    </div>
</x-chief::page.layout>
