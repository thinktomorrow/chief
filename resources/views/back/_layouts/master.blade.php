@include('back._layouts._partials.head')

<body>

<main id="main">
    @include('back._layouts._partials.nav')
    @yield('header')

    <section id="content" class="container">
        @include('back._elements.messages')
        @yield('content')
    </section>

    @include('back._modules.footer')
    @stack('sidebar')
    @stack('custom-components')

</main>

@include('back._layouts._partials.foot')
