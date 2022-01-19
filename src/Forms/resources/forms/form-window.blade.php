@foreach($getComponents() as $childComponent)
    {{ $childComponent->displayInWindow() }}
@endforeach
