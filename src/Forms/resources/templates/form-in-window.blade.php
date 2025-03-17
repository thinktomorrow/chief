<div class="@container -mx-4">
    <table>
        <tbody class="divide-y divide-grey-100 [&>*:not(:first-child)_td]:pt-3 [&>*:not(:last-child)_td]:pb-3">
            @foreach ($getComponents() as $childComponent)
                {{ $childComponent->editInSidebar() }}
            @endforeach
        </tbody>
    </table>
</div>
