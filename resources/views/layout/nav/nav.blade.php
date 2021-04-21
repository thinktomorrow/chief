<div class="h-screen flex flex-col bg-white pl-8 pr-16 py-8 divide-y divide-grey-100">
    <div class="py-8">
        <a href="{{ route('chief.back.dashboard') }}" class="link link-black">
            <x-icon-label icon="icon-home" space="large">Home</x-icon-label>
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
