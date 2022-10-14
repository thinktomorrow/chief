<x-chief::table.header :sortable="$isSortable()">
    {{ $getTitle() }}
    @if($getDescription())
        <span class="block text-sm text-grey-400">{{ $getDescription() }}</span>
    @endif
</x-chief::table.header>
