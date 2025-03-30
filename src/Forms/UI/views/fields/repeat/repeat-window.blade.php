<div class="space-y-2">
    @foreach ($getRepeatedComponents($locale ?? null) as $components)
        <div class="space-y-3 rounded-xl border border-grey-100 p-2.5">
            @foreach ($components as $childComponent)
                {{ $childComponent }}
            @endforeach
        </div>
    @endforeach
</div>
