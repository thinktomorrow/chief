@include('chief::back._layouts._partials.head')

<body>

<main id="main" class="bg-grey-lightest min-h-screen">
    @include('chief::back._layouts._partials.nav')
    @yield('header')

    <section id="content" class="container">
        @include('chief::back._elements.errors')
        @include('chief::back._elements.messages')
        @yield('content')
    </section>

    @include('chief::back._modules.footer')
    @stack('sidebar')
    @stack('custom-components')

</main>

@include('chief::back._layouts._partials.foot')
