@php
    $model = $node->getModel();
@endphp

<x-chief::table.row>
    @if($tableActionsCount > 0)
        <x-chief::table.data class="form-light">
            <x-chief::input.checkbox
                data-bulk-item-checkbox
                id="item_{{ $loop->index }}"
                name="bulk_items[]"
                value="{{ $resource->getTableRowId($model) }}"
                class="mt-1"
            />
        </x-chief::table.data>
    @endif

    @foreach ($resource->getTableRow($manager, $model) as $tableCell)
        <x-chief::table.data>
            <div class="flex items-start gap-2">
                @if ($loop->first && $level > 0)
                    <svg width="20" height="20" style="margin-top: -2px; margin-left: {{ ($level - 1) * 28 }}px;">
                        <use xlink:href="#icon-arrow-tl-to-br"/>
                    </svg>
                @endif

                {{ $tableCell->render() }}
            </div>
        </x-chief::table.data>
    @endforeach

    @if ($showOptionsColumn)
        <x-chief::table.data>
            @include('chief::manager._index._options')
        </x-chief::table.data>
    @endif
</x-chief::table.row>

@if ($node->hasChildNodes())
    @php
        $level++;
    @endphp

    @foreach($node->getChildNodes() as $child)
        @include('chief-table::nestable.table-node', ['node' => $child, 'level' => $level])
    @endforeach
@endif
