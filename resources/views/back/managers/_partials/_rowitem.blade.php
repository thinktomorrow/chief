<div class="column-6">
    <div class="row bg-white inset-s panel panel-default">
        <div>
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
        <div class="column text-right">
            {!! $manager->modelDetails()->context !!}
            @include('chief::back.managers._partials.context-menu')
        </div>
    </div>
</div>
