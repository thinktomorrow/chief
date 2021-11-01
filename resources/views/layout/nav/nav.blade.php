<div class="flex flex-col h-screen px-8 py-6 bg-white divide-y shadow-window divide-grey-100">
    <div class="py-6" style="padding-right: calc(20px + 1rem);">
        <a href="{{ route('chief.back.dashboard') }}" class="link link-black">
            <x-chief-icon-label icon="icon-home" space="large">Dashboard</x-chief-icon-label>
        </a>
    </div>

    <div class="py-6" style="padding-right: calc(20px + 1rem);">
        <nav class="flex flex-col space-y-6">
            @include('chief::layout.nav.nav-project')
            @include('chief::layout.nav.nav-general')
        </nav>
    </div>

    <div class="py-6" style="padding-right: calc(20px + 1rem);">
        <nav class="flex flex-col space-y-6">
            @include('chief::layout.nav.nav-settings')
            @include('chief::layout.nav.nav-user')
        </nav>
    </div>
</div>
