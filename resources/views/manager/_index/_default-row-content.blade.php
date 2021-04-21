<div class="flex justify-between items-center">
    @if(isset($model->created_at) && $model->created_at)
        <span class="font-medium text-grey-300">Aangemaakt op {{ $model->created_at->format('d/m/Y') }}</span>
    @endif

    @if(public_method_exists($model, 'onlineStatusAsLabel'))
        {!! $model->onlineStatusAsLabel() !!}
    @endif
</div>
