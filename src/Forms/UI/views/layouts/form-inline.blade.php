<div>

    @foreach ($getComponents() as $childComponent)
        {{ $childComponent->render() }}
    @endforeach

</div>
