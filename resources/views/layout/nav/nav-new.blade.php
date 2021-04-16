<div class="h-screen flex flex-col justify-between bg-white p-12 space-y-12">
    <div class="space-y-12">
        <div>
            @include('chief::layout.nav.logo')
        </div>

        <nav class="flex flex-col space-y-6">
            @include('chief::layout.nav.nav-project')
            @include('chief::layout.nav.nav-general')
        </nav>
    </div>

    <div class="flex flex-col space-y-6">
        @include('chief::layout.nav.nav-settings')
        @include('chief::layout.nav.nav-user')
    </div>
</div>
