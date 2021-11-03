<div class="flex items-center justify-between">
    @if(isset($model->created_at) && $model->created_at)
        <p class="text-grey-400">Aangemaakt op {{ $model->created_at->format('d/m/Y') }}</p>
    @endif

    @if(public_method_exists($model, 'onlineStatusAsLabel'))
        {!! $model->onlineStatusAsLabel() !!}
    @endif
</div>
