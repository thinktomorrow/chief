@extends('chief::back._layouts.master')

@section('page-title')
    Dashboard
@stop

@section('topbar-right')

@stop

@section('content')
    <div class="row gutter-l stack-l">
        <div class="column-4 stack-xl">
            <span class="font-bold text-5xl text-grey-500 leading-none mb-4 block">Welkom op je dashboard, {{ Auth::user()->firstname }}</span class="font-bold text-5xl leading-none mb-4 block">
            <p>Don't try to follow trends. Create them.</p>
        </div>
        <div class="gutter column-8 right">
            @foreach(app(\Thinktomorrow\Chief\Management\Managers::class)->findByTag(['page', 'dashboard']) as $manager)

                @if(!$manager->can('index')) @continue @endif

                <?php $total = method_exists($manager->indexCollection(), 'total') ? $manager->indexCollection()->total() : $manager->indexCollection()->count(); ?>

                @if($total > 0)
                    <div class="column-6">
                        <div class="rounded bg-white shadow">
                            <div class="inset">
                                <div class="flex items-center mb-4">
                                    <span class="text-4xl font-bold inline-block mr-4" style="leading-none">{{ $total }}</span>
                                    <p class="mt-2 text-lg">{{ $total == 1 ? $manager->details()->singular : $manager->details()->plural }}</p>
                                </div>
                                <a class="btn btn-primary" href="{{ $manager->route('index') }}">Ga naar {{ $manager->details()->plural }}</a>
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
