<tr wire:key="{{ $this->getRowKey($item) }}" class="">
    <td class="relative py-1 pl-4 text-left"></td>

    @foreach ($this->getColumns($item) as $column)
        <td class="py-1 pl-3 text-left">
            @continue(! $loop->first)
            <div class="flex gap-1.5">
                @if ($loop->first && isset($item->indent) && $item->indent > 0)
                    <div class="flex justify-end" style="width: {{ 20 + ($item->indent - 1) * 26 }}px">
                        <svg
                            class="h-5 w-5 text-grey-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 256 256"
                        >
                            <path
                                d="M229.66,157.66l-48,48a8,8,0,0,1-11.32-11.32L204.69,160H128A104.11,104.11,0,0,1,24,56a8,8,0,0,1,16,0,88.1,88.1,0,0,0,88,88h76.69l-34.35-34.34a8,8,0,0,1,11.32-11.32l48,48A8,8,0,0,1,229.66,157.66Z"
                            ></path>
                        </svg>
                    </div>
                @endif

                <span class="text-sm leading-5 text-grey-500">
                    @foreach ($column->getItems() as $item)
                        {{ $item }}
                    @endforeach
                </span>
            </div>
        </td>
    @endforeach

    <td class="py-1 pl-3 pr-4 text-right"></td>
</tr>