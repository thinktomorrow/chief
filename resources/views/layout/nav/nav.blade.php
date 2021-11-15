{{-- 
    TODO(tijs): 
    - It should be visible to the user which navigation item is active
    - working with the collapsible attribute feels weird (top level nav items should collapse, nested items shouldn't)
    - Collapsed state of the sidebar should be remembered between pageloads
--}}

<div 
    data-collapsible-navigation
    class="w-64 h-screen px-3 py-6 bg-white select-none shadow-sm divide-y divide-grey-100"
>
    <div class="flex items-center justify-start pb-6">
        <div 
            data-hide-all-dropdowns
            data-toggle-collapsible-navigation 
            class="flex-shrink-0 p-2 rounded-lg cursor-pointer hover:bg-primary-50"
        >
            <svg class="w-6 h-6 text-grey-700"><use xlink:href="#menu"></use></svg>
        </div>

        <span data-class-on-collapse="hidden" class="px-3 py-2 link link-black pop"> Chief </span>
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

    <div class="pt-6">
        @include('chief::layout.nav.nav-settings')
        @include('chief::layout.nav.nav-user')
    </div>
</div>

