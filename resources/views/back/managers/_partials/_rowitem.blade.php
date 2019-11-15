<div class="s-column-6 m-column-6 inset-xs">
    <div class="row bg-white border border-grey-100 rounded inset-s" style="height: 100%">
        <div class="column">
            @if($manager->can('edit'))
                <a href="{{ $manager->route('edit') }}" class="flex items-center">
                    <h3 class="mb-0">{!! $manager->details()->title !!}</h3>
                    @if(\Thinktomorrow\Chief\Settings\Homepage::is($manager->model()))
                        <span class="label label-tertiary flex items-center ml-2">
                            <svg width="14" height="14" class="fill-current"><use xlink:href="#home"/></svg>
                            <span class="ml-2 text-sm">homepage</span>
                        </span>
                    @endif
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
                <div class="stack-s text-sm">
                    {!! $manager->details()->intro !!}
                </div>
            @endif
            @if($manager->model() instanceof Thinktomorrow\Chief\Modules\Module)
                <div class="stack-s">{{$manager->details()->singular}}</div>
            @endif
        </div>
        <div class="column-3 text-right flex flex-col justify-between items-end">
            @if($manager->can('update'))
                @include('chief::back.managers._partials.context-menu')
            @endif
            {!! $manager->details()->context !!}
        </div>
    </div>
</div>
