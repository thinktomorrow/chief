@include('chief::back._layouts._partials.head')

<body>

<main id="main">

    <section id="content" class="container center-center" style="min-height:100vh;">

        <div>
            <div class="stack text-center">
                <a href="{{ route('chief.back.login') }}">
                    <img alt="Logo" src="{{ asset('/assets/back/img/logo.svg') }}" width="140" height="53">
                </a>
            </div>

            @include('chief::back._elements.messages')
            @yield('content')
        </div>

    </section>

    @stack('custom-components')

</main>

@include('chief::back._layouts._partials.foot')
