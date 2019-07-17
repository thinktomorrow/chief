@section('header')
    <header class="bg-white border-b border-secondary-200">
        <div class="container">
            @if(isset($subtitle))
                <div class="row stack">
                    <div>{!! $subtitle !!}</div>
                </div>
            @endif
            <div class="row stack flex justify-between">

                <div>

                    <h1>{!! $title ?? '' !!} </h1>
                    {{ $extra ??  '' }}

                </div>

                <div class="text-right center-y">
                    {{ $slot }}
                </div>

            </div>
        </div>
    </header>
@endsection
