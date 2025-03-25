<div class="@container">
    <table class="w-full">
        <tbody
            @class(['divide-y divide-grey-100', '@lg:[&>*:not(:first-child)_td]:pt-3 @lg:[&>*:not(:last-child)_td]:pb-3'])
        >
            @foreach ($getComponents() as $childComponent)
                {{ $childComponent->editInSidebar() }}
            @endforeach
        </tbody>
    </table>
</div>
