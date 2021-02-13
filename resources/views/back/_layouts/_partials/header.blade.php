@section('header')
    <header class="my-10">
        @if(isset($breadcrumbs))
            <div class="container mb-2">
                <div class="row">
                    <div class="column-12">
                        {!! $breadcrumbs !!}
                    </div>
                </div>
            </div>
        @endif

        <div class="container">
            <div class="row justify-between items-center">
                <div class="column-9">
                    <h1 class="flex items-center mb-0">
                        <span>{!! $subtitle ?? '' !!}</span>
                        <span>{!! ucfirst($title) ?? '' !!}</span>
                    </h1>

                    {{ $extra ??  '' }}
                </div>

                <div class="column-3 text-right justify-end center-y">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </header>
@endsection
