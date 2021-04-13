<div class="s-column-6 m-column-4 inset-xs">
    <div class="row bg-white border border-grey-100 rounded inset-s">
        <div class="column">

            <h3>{!! $manager->details()->title !!}</h3>

            @if($manager->details()->subtitle)
                <div>
                    <span class="text-grey-500">{!! $manager->details()->subtitle !!}</span>
                </div>
            @endif

            <div class="stack-s font-s">
                <div>
                    In archief sinds {{ $manager->assistant('archive')->archivedAt()->format('d/m/Y H:i') }}
                </div>

                <div>
                    {!! $manager->details()->intro !!}
                </div>
            </div>

        </div>

        <div class="column-3 text-right flex flex-col justify-between items-end">
            @if($manager->can('update'))
                @include('chief::back.managers.archive.context-menu')
            @endif
            <span>{!! $manager->details()->context !!}</span>
        </div>

    </div>
</div>