@foreach ($getComponents() as $childComponent)
    {{ $childComponent->renderPreview() }}
@endforeach
