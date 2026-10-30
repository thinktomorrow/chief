@props ([
    'tree',
    'node',
    'label',
    'children',
    'guide' => null,
    'headings' => [],
    'openIds' => [],
])

{{-- The active guide opens to its headings and child guides; its ancestors are open as well. --}}
<x-chief::nav.item
    :label="e($label)"
    :url="$node->hasPage() ? route('chief.docs.show', $node->id) : null"
    :is-active="$node->id === $guide?->id"
    :open="in_array($node->id, $openIds, true)"
>
    @if ($node->id === $guide?->id)
        @foreach ($headings as $heading)
            <x-chief::nav.item :label="e($heading->title)" :url="'#'.$heading->id" />
        @endforeach
    @endif

    @foreach ($children as $child)
        <x-chief-docs::sidebar-item
            :tree="$tree"
            :node="$child"
            :label="$child->title"
            :children="$tree->children($child)"
            :guide="$guide"
            :headings="$headings"
            :open-ids="$openIds"
        />
    @endforeach
</x-chief::nav.item>
