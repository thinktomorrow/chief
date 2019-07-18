@extends('chief::back._layouts.master')

@section('page-title')
    Dashboard
@stop

@section('topbar-right')

@stop

@section('content')
    <div class="row gutter-l stack-l">
        <div class="column-4 stack-xl">
            <h1>Welkom op je dashboard, {{ Auth::user()->firstname }}</h1>
            <p>Don't try to follow trends. Create them</p>
        </div>
        <div class="gutter column-8 right">
            @foreach(app(\Thinktomorrow\Chief\Management\Managers::class)->findByTag(['page', 'dashboard']) as $manager)

                @if(!$manager->can('index')) @continue @endif

                @if($manager->findAllManaged()->count() > 0)
                    <div class="column-6">
                        <div class="border border-grey-100 rounded --raised bg-white">
                            <div class="inset">
                                <div class="stack">
                                    <div class="flex items-center mb-4">
                                        <h1 class="mb-0 mr-4">{{ $manager->findAllManaged()->count() }}</h1>
                                        <p>{{ $manager->findAllManaged()->count() == 1 ? $manager->details()->singular : $manager->details()->plural }}</p>
                                    </div>
                                    <a class="btn btn-primary" href="{{ $manager->route('index') }}">Ga naar {{ $manager->details()->plural }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @endforeach
        </div>
    </div>
@stop

@section('chief-footer')
    @include('chief::back._layouts._partials.chief-footer')
@stop