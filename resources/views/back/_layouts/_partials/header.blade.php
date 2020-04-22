@section('header')
    <header class="bg-white border-b border-secondary-200 sticky top-0" style="z-index:2;">
        <div class="container">
            <div class="row stack flex justify-between">
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
