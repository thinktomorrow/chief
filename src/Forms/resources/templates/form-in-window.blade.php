{{--
    <div class="divide-y divide-grey-100">
    @foreach ($getComponents() as $childComponent)
    <div @class(['pt-4' => ! $loop->first, 'pb-4' => ! $loop->last])>
    {{ $childComponent->editInSidebar() }}
    </div>
    @endforeach
    </div>
--}}

<div class="@container">
    <table>
        <tbody class="divide-y divide-grey-100">
            @foreach ($getComponents() as $childComponent)
                {{ $childComponent->editInSidebar() }}
            @endforeach
        </tbody>
    </table>
</div>
