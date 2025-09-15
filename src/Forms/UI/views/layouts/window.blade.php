@if (count($getComponents()) > 0)
    <x-chief::window :title="$getTitle()" :description="$getDescription()" :variant="$getVariant()">
        @foreach ($getComponents() as $childComponent)
            {{ $childComponent }}
        @endforeach
    </x-chief::window>
@endif
