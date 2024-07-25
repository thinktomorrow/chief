<tr wire:key="ancestor-row" class="bg-grey-50">
    <td class="relative py-2 pl-4 text-left"></td>
    <td class="relative py-2 pl-3 text-left">

        <span class="flex gap-1.5 text-sm leading-5 text-grey-700">

        @foreach($ancestors as $ancestor)

                @php
                    $columns = $this->getColumns($ancestor);
                    $firstColumn = reset($columns);
                @endphp

                @foreach($firstColumn->getItems() as $item)
                    {{$item}}
                @endforeach

                @if(! $loop->last)
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5"
                    >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3"
                    />
                </svg>
                @endif
            @endforeach
        </span>
    </td>
    <td class="relative py-2 pl-3 text-left"></td>
    <td class="relative py-2 pl-3 text-left"></td>
    <td class="relative py-2 pl-3 pr-4 text-left"></td>
</tr>
