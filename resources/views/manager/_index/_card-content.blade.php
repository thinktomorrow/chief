@php
    $showCreatedAt = (isset($model->created_at) && $model->created_at);
    $showPageState = $model instanceof \Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
@endphp

@if($showCreatedAt || $showPageState)
    <div class="flex items-center justify-between">
        @if($showCreatedAt)
            <p class="text-grey-400">Aangemaakt op {{ $model->created_at->format('d/m/Y') }}</p>
        @endif

        @if($showPageState)
            @foreach($model->getStateKeys() as $stateKey)
                {!! $model->getStateConfig($stateKey)->getStateLabel($model) !!}
            @endforeach
        @endif
    </div>
@endif
