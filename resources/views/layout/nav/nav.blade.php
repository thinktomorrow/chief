<div class="flex flex-col h-screen py-8 pl-8 pr-16 bg-white divide-y shadow-sm divide-grey-100">
    <div class="py-8">
        <a href="{{ route('chief.back.dashboard') }}" class="link link-black">
            <x-chief-icon-label icon="icon-home" space="large">Dashboard</x-chief-icon-label>
        </a>
    </div>

    <div class="py-8">
        <nav class="flex flex-col space-y-6">
            @include('chief::layout.nav.nav-project')
            @include('chief::layout.nav.nav-general')
        </nav>
    </div>

    <div class="py-8">
        <nav class="flex flex-col space-y-6">
            @include('chief::layout.nav.nav-settings')
            @include('chief::layout.nav.nav-user')
        </nav>
    </div>
</div>
