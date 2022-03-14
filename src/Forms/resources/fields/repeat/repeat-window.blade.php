<div class="space-y-3">
    @foreach($getRepeatedComponents($locale ?? null) as $components)
        <div class="p-3 space-y-3 border rounded-lg border-grey-100">
            @foreach($components as $childComponent)
                {{ $childComponent->displayInWindow() }}
            @endforeach
        </div>
    @endforeach
</div>

