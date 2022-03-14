<div data-repeat-section class="flex justify-between border border-grey-100 rounded p-2 my-2">
    <span data-sortable-handle title="sorteer dit blokje">
        <svg width="16" height="16"><use xlink:href="#icon-drag"/></svg>
    </span>
    <div class="w-5/6">
        @foreach($components as $childComponent)
            {{ $childComponent }}
        @endforeach
    </div>
    <span class="inline-block cursor-pointer" data-delete-repeat-section title="Verwijder dit blokje">
        <svg width="16" height="16"><use xlink:href="#icon-trash"/></svg>
    </span>
</div>
