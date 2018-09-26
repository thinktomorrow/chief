<div class="row bg-white inset panel panel-default stack">
    <div class="column-9">

        <a class="text-black bold" href="{{ $manager->route('edit') }}">
            {{ $manager->managedModelDetails()->title }}
        </a>

        <div>
            <span class="text-subtle">{{ $manager->managedModelDetails()->subtitle }}</span>
        </div>
        <div class="stack-s font-s">
            {{ $manager->managedModelDetails()->intro }}
        </div>
    </div>
    <div class="column-3 text-right">
        @include('chief::back.managers._partials.context-menu')
    </div>
</div>