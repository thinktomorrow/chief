@section('header')
    <header class="container sticky sticky-bar stack">
        <div class="row stack">
            <div class="column inline-s">
                <h1 class="inline-s">
                    @if(isset($back))
                        <a class="title-back left inline-s" href="{{ $back }}"><i class="icon icon-chevron-left"></i></a>
                    @else
                        <a class="title-back left inline-s" href="{{ url()->previous() }}"><i class="icon icon-chevron-left"></i></a>
                    @endif
                    {{ $title ?? '' }}
                </h1>
                {!! isset($subtitle) ? '<p>'.$subtitle.'</p>' : '' !!}
                {{ $extra ??  '' }}
            </div>
            <div class="column text-right">
                {{ $actionbuttons ??  '' }}
                {{ $slot }}
            </div>
        </div>
    </header>
@endsection
