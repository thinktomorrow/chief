<div class="flex flex-col h-screen bg-white shadow-sm window-md">
    <div class="window-sm-y" style="padding-right: calc(20px + 1rem);">
        <a href="{{ route('chief.back.dashboard') }}" class="link link-black">
            <x-chief-icon-label icon="icon-home" space="large">Dashboard</x-chief-icon-label>
        </a>
    </div>

    <hr class="-window-sm-x text-grey-100">

    <div class="window-sm-y" style="padding-right: calc(20px + 1rem);">
        <nav class="flex flex-col space-y-6">
            @include('chief::layout.nav.nav-project')
            @include('chief::layout.nav.nav-general')
        </nav>
    </div>

    <hr class="-window-sm-x text-grey-100">

    <div class="window-sm-y" style="padding-right: calc(20px + 1rem);">
        <nav class="flex flex-col space-y-6">
            @include('chief::layout.nav.nav-settings')
            @include('chief::layout.nav.nav-user')
        </nav>
    </div>
</div>
