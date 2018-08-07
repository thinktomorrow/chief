@section('header')
    <header class="sticky sticky-bar">
        <div class="container ">
        <div class="row stack">
            <div class="column squished-s">
                <h1 class="--remove-margin">
                    {{ $title ?? '' }}
                </h1>
                {!! isset($subtitle) ? '<div class="font-s">'.$subtitle.'</div>' : '' !!}
                {{ $extra ??  '' }}
            </div>
            <div class="text-right center-y">
                {{ $slot }}
            </div>
        </div>
        </div>
    </header>
@endsection
