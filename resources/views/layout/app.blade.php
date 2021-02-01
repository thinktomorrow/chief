@include('chief::layout.partials.head')

<main id="main" class="bg-secondary-50 min-h-screen relative">

    @include('chief::layout.nav.nav')

    @isset($title)
        {!! $title !!}
    @endisset
    @yield('header')

    {!! $slot !!}

    @include('chief::layout.partials.totem')
</main>

@include('chief::layout.partials.footer')

