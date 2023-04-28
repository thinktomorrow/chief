<form data-bulk-action-element method="GET" action="{{ $getEndpoint() }}" class="hidden">
    <input data-bulk-action-item-field type="hidden" name="bulk_items">

    <button class="inline-flex items-start gap-2 btn btn-grey" type="submit">
        @if ($getIcon())
            <span class="[&>*]:w-5 [&>*]:h-5">
                {!! $getIcon() !!}
            </span>

            {{ $getTitle() }}
        @else
            {{ $getTitle() }}
        @endif
    </button>
</form>
