<form method="GET" action="{{ $getEndpoint() }}">
    <input data-bulk-action-item-field type="hidden" name="bulk_items">
    <button class="text-left w-full dropdown-link" type="submit">{{ $getTitle() }}</button>
</form>
