<div data-repeat-section class="flex items-start gap-3 rounded-lg border border-grey-100 p-3">
    <x-chief::button size="sm" data-sortable-handle class="shrink-0">
        <x-chief::icon.drag-drop-vertical />
    </x-chief::button>

    <div class="my-1 w-full space-y-4">
        @foreach ($components as $childComponent)
            {{ $childComponent }}
        @endforeach
    </div>

    <x-chief::button size="sm" variant="outline-red" data-delete-repeat-section class="shrink-0">
        <x-chief::icon.delete />
    </x-chief::button>
</div>
