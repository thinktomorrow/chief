<div class="row bg-white inset-s panel panel-default stack-s">
    <div class="column-9">

        @if($manager->can('edit'))
            <a class="text-black bold" href="{{ $manager->route('edit') }}">
                {!! $manager->modelDetails()->title !!}
            </a>
        @else
            {!! $manager->modelDetails()->title !!}
        @endif

        @if($manager->modelDetails()->subtitle)
            <div>
                <span class="text-subtle">{!! $manager->modelDetails()->subtitle !!}</span>
            </div>
        @endif
        @if($manager->modelDetails()->intro)
            <div class="stack-s font-s">
                {!! $manager->modelDetails()->intro !!}
            </div>
        @endif
    </div>
    <div class="column-3 text-right">
        @include('chief::back.managers._partials.context-menu')
    </div>
</div>