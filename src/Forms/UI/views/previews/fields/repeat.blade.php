<div class="space-y-2">
    @foreach ($getRepeatedComponents($locale ?? null) as $components)
        <div class="divide-y divide-grey-100 rounded-xl border border-grey-100 px-3 py-2">
            @foreach ($components as $childComponent)
                {{ $childComponent->renderPreview() }}
            @endforeach
        </div>
    @endforeach
</div>
