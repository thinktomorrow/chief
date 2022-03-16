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
    class="fixed inset-0 hidden bg-white lg:static lg:block animate-slide-in-nav lg:animate-none"
>
    <div
        data-collapsible-navigation
        class="h-screen px-3 border-r divide-y select-none divide-grey-100 border-grey-100"
    >
        {{-- Desktop Chief title --}}
        <div class="items-center justify-start hidden py-6 lg:flex">
            <div
                data-toggle-navigation
                class="p-2 rounded-lg cursor-pointer shrink-0 hover:bg-primary-50"
            >
                <svg class="w-6 h-6 text-black"><use xlink:href="#menu"></use></svg>
            </div>

            <span
                data-toggle-classes="hidden"
                class="inline-block px-3 py-2 font-semibold text-black {{ $isCollapsedOnPageLoad ? 'hidden' : null }}"
            > Chief </span>
        </div>

        {{-- Mobile Chief title --}}
        <div class="flex items-center justify-start py-6 lg:hidden">
            <div
                data-mobile-navigation-toggle
                class="p-2 rounded-lg cursor-pointer shrink-0 hover:bg-primary-50"
            >
                <svg class="w-6 h-6 text-black"><use xlink:href="#icon-arrow-rtl"></use></svg>
            </div>

            <span class="inline-block px-3 py-2 font-semibold text-black"> Chief </span>
        </div>

        <div class="py-6">
            <x-chief::nav.item
                label="Dashboard"
                url="{{ route('chief.back.dashboard') }}"
                icon="<svg><use xlink:href='#icon-home'></use></svg>"
                collapsible
            />

            @include('chief::layout.nav.nav-project')
            @include('chief::layout.nav.nav-general')
        </div>

        <div class="py-6">
            @include('chief::layout.nav.nav-settings')
            @include('chief::layout.nav.nav-user')
        </div>
    </div>
</div>
