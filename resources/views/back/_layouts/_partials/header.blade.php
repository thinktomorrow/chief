@section('header')
    <div class="my-12">
        <div class="container space-y-2">
            @if(isset($breadcrumbs))
                <div class="row">
                    <div class="w-full">
                        {!! $breadcrumbs !!}
                    </div>
                </div>
            @endif

            <div class="row-between-center">
                <div class="w-3/4">
                    <h1 class="flex items-center">
                        <span>{!! $subtitle ?? '' !!}</span>
                        <span>{!! ucfirst($title) ?? '' !!}</span>
                    </h1>

                    {{ $extra ??  '' }}
                </div>

                <div class="w-1/4 flex justify-end items-center">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
@endsection
