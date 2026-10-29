<ul class="flex flex-col gap-3">
    @foreach ($nodes as $node)
        <li class="flex flex-col gap-3">
            @if ($node->hasPage())
                <x-chief::link variant="blue" :href="route('chief.docs.show', $node->id)">
                    {{ $node->title }}
                </x-chief::link>
            @else
                <span class="text-grey-950 font-medium">{{ $node->title }}</span>
            @endif

            @if ($children = $tree->children($node))
                <div class="pl-4">
                    @include ('chief-docs::tree', ['nodes' => $children])
                </div>
            @endif
        </li>
    @endforeach
</ul>
