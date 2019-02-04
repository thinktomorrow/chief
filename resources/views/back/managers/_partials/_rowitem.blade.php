<div class="s-column-6 m-column-4 l-column-3 inset-xs">
    <div class="row bg-white inset-s panel panel-default" style="height:100%">
        <div class="column">
            @if($manager->can('edit'))
                <a class="text-black bold" href="{{ $manager->route('edit') }}">
                    {!! $manager->details()->title !!}
                </a>
            @else
                {!! $manager->details()->title !!}
            @endif

            @if($manager->details()->subtitle)
                <div>
                    <span class="text-subtle">{!! $manager->details()->subtitle !!}</span>
                </div>
            @endif
            @if($manager->details()->intro)
                <div class="stack-s font-s">
                    {!! $manager->details()->intro !!}
                </div>
            @endif
        </div>
        <div class="column-4 text-right">
            {!! $manager->details()->context !!}
            @include('chief::back.managers._partials.context-menu')
        </div>
    </div>
</div>

