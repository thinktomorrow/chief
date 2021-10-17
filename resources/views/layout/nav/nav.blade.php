<div data-collapsible-navigation class="w-64 h-screen p-4 bg-white">
    <div data-toggle-collapsible-navigation class="px-4 py-2 rounded-lg cursor-pointer hover:bg-grey-100">
        <div class="flex items-center justify-start space-x-4">
            <div class="flex-shrink-0">
                <svg width="20" height="20"><use xlink:href="#icon-home"></use></svg>
            </div>

            <span data-hide-on-collapse class="link link-black pop"> Dashboard </span>
        </div>
    </div>

    <div class="px-4 py-2 rounded-lg cursor-pointer hover:bg-grey-100">
        <div class="flex items-center justify-start space-x-4">
            <div class="flex-shrink-0">
                <svg width="20" height="20"><use xlink:href="#icon-collection"></use></svg>
            </div>

            <span data-hide-on-collapse class="link link-black pop"> Pagina's </span>
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
