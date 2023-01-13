<div data-repeat-section class="flex gap-3 p-3 border rounded-lg border-grey-100">
    <span data-sortable-handle class="cursor-pointer shrink-0">
        <x-chief-icon-button icon="icon-chevron-up-down" color="grey" />
    </span>

    <div class="w-full my-1 space-y-4">
        @foreach($components as $childComponent)
            {{ $childComponent }}
        @endforeach
    </div>

    <span data-delete-repeat-section class="cursor-pointer shrink-0">
        <x-chief-icon-button icon="icon-trash" color="grey" />
    </span>
</div>
