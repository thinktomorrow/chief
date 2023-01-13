<div class="space-y-6">
    @foreach($getComponents() as $childComponent)
        {{ $childComponent->editInSidebar() }}
    @endforeach
</div>
