<div class="-mx-3 p-3 rounded-lg bg-white">
    <div class="space-y-6">
        @foreach($getComponents() as $childComponent)
            {{ $childComponent->editInSidebar() }}
        @endforeach
    </div>
</div>


