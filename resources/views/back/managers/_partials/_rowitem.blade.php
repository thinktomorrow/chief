<div class="s-column-6 m-column-4 inset-xs">
    <div class="row bg-white border border-grey-100 rounded inset-s" style="height: 100%">
        <div class="column">
            @if($manager->can('edit'))
                <a class="text-black font-bold" href="{{ $manager->route('edit') }}">
                    {!! $manager->details()->title !!}
                </a>
            @else
                <span class="text-black font-bold">{!! $manager->details()->title !!}</span>
            @endif
            @if($manager->details()->subtitle)
                <div>
                    <span class="text-grey-300">{!! $manager->details()->subtitle !!}</span>
                </div>
            @endif
            @if($manager->details()->intro)
                <div class="stack-s font-s">
                    {!! $manager->details()->intro !!}
                </div>
            @endif
            @if($manager->model() instanceof Thinktomorrow\Chief\Modules\Module)
                <div class="stack-s">{{$manager->details()->singular}}</div>
            @endif
        </div>
        <div class="column-3 text-right flex flex-col justify-between items-end">
            @include('chief::back.managers._partials.context-menu')
            {!! $manager->details()->context !!}
        </div>
    </div>
</div>
