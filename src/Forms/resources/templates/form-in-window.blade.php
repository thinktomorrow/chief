<div class="space-y-4">
    @foreach($getComponents() as $childComponent)
        {{ $childComponent->editInSidebar() }}
    @endforeach
</div>
