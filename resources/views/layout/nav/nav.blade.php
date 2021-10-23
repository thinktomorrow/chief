<div data-collapsible-navigation class="w-64 h-screen p-4 bg-white shadow-sm select-none">
    <div data-toggle-collapsible-navigation data-hide-all-dropdowns class="pb-6 mb-6 border-b border-grey-100">
        <div class="flex items-center justify-start">
            <div class="flex-shrink-0 px-3 py-2 rounded-lg cursor-pointer hover:bg-primary-50">
                <svg width="22" height="22" class="text-grey-700"><use xlink:href="#menu"></use></svg>
            </div>

            <span data-hide-on-collapse class="ml-2 leading-tight link link-black pop"> Chief </span>
        </div>
    </div>

    <div class="px-3 py-2 rounded-lg cursor-pointer hover:bg-primary-50">
        <div class="flex items-center justify-start space-x-6">
            <div class="flex-shrink-0">
                <svg width="22" height="22" class="text-grey-700"><use xlink:href="#icon-home"></use></svg>
            </div>

            <span data-hide-on-collapse class="leading-tight link link-black pop"> Dashboard </span>
        </div>
    </div>

    @include('chief::layout.nav.nav-item')

    <div class="px-3 py-2 rounded-lg cursor-pointer hover:bg-primary-50">
        <div class="flex items-center justify-start space-x-6">
            <div class="flex-shrink-0">
                <svg width="22" height="22" class="text-grey-700"><use xlink:href="#icon-book-open"></use></svg>
            </div>

            <span data-hide-on-collapse class="leading-tight link link-black pop"> Blog </span>
        </div>
    </div>

    <div class="pt-6 mt-6 border-t border-grey-100">
        <div class="px-3 py-2 rounded-lg cursor-pointer hover:bg-primary-50">
            <div class="flex items-center justify-start space-x-6">
                <div class="flex-shrink-0">
                    <svg width="22" height="22" class="text-grey-700"><use xlink:href="#icon-settings"></use></svg>
                </div>

                <span data-hide-on-collapse class="leading-tight link link-black pop"> Instellingen </span>
            </div>
        </div>

        <div class="px-3 py-2 rounded-lg cursor-pointer hover:bg-primary-50">
            <div class="flex items-center justify-start space-x-6">
                <div class="flex-shrink-0">
                    <svg width="22" height="22" class="text-grey-700"><use xlink:href="#icon-user"></use></svg>
                </div>

                <span data-hide-on-collapse class="leading-tight link link-black pop"> Tijs </span>
            </div>
        </div>

        <div class="px-3 py-2 rounded-lg cursor-pointer hover:bg-primary-50">
            <div class="flex items-center justify-start space-x-6">
                <div class="flex-shrink-0">
                    <svg width="22" height="22" class="text-grey-700"><use xlink:href="#icon-logout"></use></svg>
                </div>

                <span data-hide-on-collapse class="leading-tight link link-black pop"> Logout </span>
            </div>
        </div>
    </div>
</div>

{{-- <div class="flex flex-col h-screen bg-white shadow-sm window-md">
    <div class="py-6" style="padding-right: calc(20px + 1rem);">
        <a href="{{ route('chief.back.dashboard') }}" class="link link-black">
            <x-chief-icon-label icon="icon-home" space="large">Dashboard</x-chief-icon-label>
        </a>
    </div>

    <hr class="-window-x text-grey-100">

    <div class="py-6" style="padding-right: calc(20px + 1rem);">
        <nav class="flex flex-col space-y-6">
            @include('chief::layout.nav.nav-project')
            @include('chief::layout.nav.nav-general')
        </nav>
    </div>

    <hr class="-window-x text-grey-100">

    <div class="py-6" style="padding-right: calc(20px + 1rem);">
        <nav class="flex flex-col space-y-6">
            @include('chief::layout.nav.nav-settings')
            @include('chief::layout.nav.nav-user')
        </nav>
    </div>
</div> --}}
