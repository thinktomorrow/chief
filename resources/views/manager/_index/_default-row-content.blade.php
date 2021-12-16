@php
    $showCreatedAt = (isset($model->created_at) && $model->created_at);
    $showOnlineStatus = public_method_exists($model, 'onlineStatusAsLabel');
@endphp

@if($showCreatedAt || $showOnlineStatus)
    <div class="flex items-center justify-between">
        @if($showCreatedAt)
            <p class="text-grey-400">Aangemaakt op {{ $model->created_at->format('d/m/Y') }}</p>
        @endif

        @if($showOnlineStatus)
            {!! $model->onlineStatusAsLabel() !!}
        @endif
    </div>
@endif
