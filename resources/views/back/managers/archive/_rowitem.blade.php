<div class="column-6">
    <div class="row bg-white inset-s panel panel-default">
        <div>
            {!! $manager->details()->title !!}

            @if($manager->details()->subtitle)
                <div>
                    <span class="text-subtle">{!! $manager->details()->subtitle !!}</span>
                </div>
            @endif
            <div class="stack-s font-s">
                In archief sinds {{ $manager->assistant('archive')->archivedAt()->format('d/m/Y H:i') }}
                {!! $manager->details()->intro !!}
            </div>
        </div>
        <div class="column text-right">
            {!! $manager->details()->context !!}
            @include('chief::back.managers.archive.context-menu')
        </div>
    </div>
</div>
