<form method="GET" action="{{ $getEndpoint() }}">
    <input data-bulk-action-item-field type="hidden" name="bulk_items">

    <button class="btn btn-grey icon-label" type="submit">
        @if ($getIcon())
            <x-chief-icon-label :icon='$getIcon()'>
                {{ $getTitle() }}
            </x-chief-icon-label>
        @else
            {{ $getTitle() }}
        @endif
    </button>
</form>
