<div class="h-screen flex flex-col justify-between bg-white p-12 space-y-12">
    <div class="space-y-12">
        <div>
            <a href="{{ route('chief.back.dashboard') }}" class="link link-black">
                <x-icon-label icon="icon-home" space="large">Home</x-icon-label>
            </a>
        </div>

        <nav class="flex flex-col space-y-6">
            @include('chief::layout.nav.nav-project')
            @include('chief::layout.nav.nav-general')
        </nav>
    </div>

    <nav class="flex flex-col space-y-6">
        @include('chief::layout.nav.nav-settings')
        @include('chief::layout.nav.nav-user')
    </nav>
</div>