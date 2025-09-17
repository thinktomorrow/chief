<tr wire:key="ancestor-row" class="bg-grey-50 *:py-2 *:pl-3 [&>*:first-child]:pl-4 [&>*:last-child]:pr-4">
    @if ($this->hasAnyBulkActions())
        <td></td>
    @endif

    <td colspan="9999" class="text-left">
        <div class="text-grey-700 flex gap-1.5 text-sm leading-5">
            @foreach ($ancestors as $ancestor)
                {{ $this->getAncestorTreeLabel($ancestor) }}

                @if (! $loop->last)
                    <x-chief::icon.chevron-right class="my-1 size-3" />
                @endif
            @endforeach
        </div>
    </td>
</tr>
