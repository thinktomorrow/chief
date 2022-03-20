@php
    $showCreatedAt = (isset($model->created_at) && $model->created_at);
    $showPageState = public_method_exists($model, 'pageStateAsLabel');
@endphp

@if($showCreatedAt || $showPageState)
    <div class="flex items-center justify-between">
        @if($showCreatedAt)
            <p class="text-grey-400">Aangemaakt op {{ $model->created_at->format('d/m/Y') }}</p>
        @endif

        @if($showPageState)
            {!! $model->pageStateAsLabel() !!}
        @endif
    </div>
@endif
