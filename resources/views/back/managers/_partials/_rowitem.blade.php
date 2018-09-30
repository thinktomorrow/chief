<div class="row bg-white inset-s panel panel-default stack-s">
    <div class="column-9">

        @if($manager->can('edit'))
            <a class="text-black bold" href="{{ $manager->route('edit') }}">
                {!! $manager->managedModelDetails()->title !!}
            </a>
        @else
            {!! $manager->managedModelDetails()->title !!}
        @endif

        @if($manager->managedModelDetails()->subtitle)
            <div>
                <span class="text-subtle">{!! $manager->managedModelDetails()->subtitle !!}</span>
            </div>
        @endif
        @if($manager->managedModelDetails()->intro)
            <div class="stack-s font-s">
                {!! $manager->managedModelDetails()->intro !!}
            </div>
        @endif
    </div>
    <div class="column-3 text-right">
        @include('chief::back.managers._partials.context-menu')
    </div>
</div>