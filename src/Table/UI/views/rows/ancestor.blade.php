<tr wire:key="ancestor-row" class="bg-grey-50 *:py-2 *:pl-3 [&>*:first-child]:pl-4 [&>*:last-child]:pr-4">
    <td x-show="showCheckboxes"></td>

    <td colspan="9999" class="text-left">
        <div class="flex gap-1.5 text-sm leading-5 text-grey-700">
            @foreach ($ancestors as $ancestor)
                {{ $this->getAncestorTreeLabel($ancestor) }}

                @if (! $loop->last)
                    <svg class="my-1 size-3">
                        <use xlink:href="#icon-chevron-right"></use>
                    </svg>
                @endif
            @endforeach
        </div>
    </td>
</tr>
