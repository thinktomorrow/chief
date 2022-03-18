<div class="divide-y divide-grey-100">
    @foreach($getComponents() as $childComponent)
        <div @class(['pt-4' => !$loop->first, 'pb-4' => !$loop->last])>
            {{ $childComponent->editInSidebar() }}
        </div>
    @endforeach
</div>
