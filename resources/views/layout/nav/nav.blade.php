<div
    data-collapsible-navigation
    class="h-screen px-3 divide-y shadow-sm select-none divide-grey-100"
>
    {{-- Desktop Chief title --}}
    <div class="items-center justify-start hidden py-6 lg:flex">
        <div
            data-toggle-navigation
            class="flex-shrink-0 p-2 rounded-lg cursor-pointer hover:bg-primary-50"
        >
            <svg class="w-6 h-6 text-grey-700"><use xlink:href="#menu"></use></svg>
        </div>

        <span
            data-toggle-classes="hidden"
            class="px-3 py-2 link link-black {{ $isCollapsedOnPageLoad ? 'hidden' : null }}"
        > Chief </span>
    </div>

    {{-- Mobile Chief title --}}
    <div class="flex items-center justify-start py-6 lg:hidden">
        <div
            data-mobile-navigation-toggle
            class="flex-shrink-0 p-2 rounded-lg cursor-pointer hover:bg-primary-50"
        >
            <svg class="w-6 h-6 text-grey-700"><use xlink:href="#icon-arrow-rtl"></use></svg>
        </div>

        <span class="px-3 py-2 link link-black"> Chief </span>
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
