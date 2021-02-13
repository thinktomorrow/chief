<nav class="bg-grey-50">
    <div class="container">
        <div class="row justify-between">
            <ul class="navigation-list flex items-center">
                @include('chief::layout.nav.logo')
                @include('chief::layout.nav.nav-project')
                @include('chief::layout.nav.nav-general')
            </ul>

            <ul class="navigation-list flex float-right items-center">
                @include('chief::layout.nav.nav-settings')
            </ul>
        </div>
    </div>
</nav>
