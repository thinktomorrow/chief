@section('header')
    <header class="bg-white border-b border-grey-200">
        <div class="container">
            <div class="row stack justify-between">

                <div class="squished-s">

                    <h1>{!! $title ?? '' !!}</h1>
                    
                    {!! isset($subtitle) ? $subtitle : '' !!}

                    {{ $extra ??  '' }}

                </div>

                <div class="text-right center-y">
                    {{ $slot }}
                </div>

            </div>
        </div>
    </header>
@endsection
