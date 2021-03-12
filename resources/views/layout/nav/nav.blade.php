<nav class="bg-grey-50 py-6">
    <div class="container">
        <div class="row justify-between">
            <ul class="flex items-center space-x-12">
                @include('chief::layout.nav.logo')
                @include('chief::layout.nav.nav-project')
                @include('chief::layout.nav.nav-general')
            </ul>

            <ul class="flex items-center space-x-12">
                @include('chief::layout.nav.nav-settings')
            </ul>
        </div>
    </div>
</nav>
