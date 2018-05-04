@section('header')
    <header class="sticky sticky-bar">
        <div class="container ">
        <div class="row stack-s">
            <div class="column">
                <h1 class="--remove-margin">
                    @if(isset($back))
                        <a class="btn btn-link text-primary" href="{{ $back }}"><i class="icon icon-chevron-left"></i></a>
                    @else
                        <a class="btn btn-link text-primary" href="{{ url()->previous() }}"><i class="icon icon-chevron-left"></i></a>
                    @endif
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
