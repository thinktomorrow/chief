@section('header')
    <header class="sticky sticky-bar">
        <div class="container ">
        <div class="row stack">
            <div class="column squished-s">
                <h1 class="--remove-margin">
                    {{ $title ?? '' }}
                </h1>
                {!! isset($subtitle) ? '<p>'.$subtitle.'</p>' : '' !!}
                {{ $extra ??  '' }}
            </div>
            <div class="text-right center-y">
                {{ $actionbuttons ??  '' }}
                {{ $slot }}
            </div>
        </div>
        </div>
    </header>
@endsection
