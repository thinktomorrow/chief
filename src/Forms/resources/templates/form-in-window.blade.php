@foreach($getComponents() as $childComponent)
    {{ $childComponent->editInSidebar() }}
@endforeach
