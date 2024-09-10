<tr
    data-table-row="{{ $this->getRowKey($item) }}"
    wire:key="{{ $this->getRowKey($item) }}"
    :class="{ 'bg-grey-50': selection.includes('{{ $this->getRowKey($item) }}') }"
>
    <td
        class="relative py-1.5 pl-4 text-left"
        :class="{ 'before:absolute before:block before:top-0 before:bottom-0 before:left-0 before:w-px before:bg-primary-500': selection.includes('{{ $this->getRowKey($item) }}') }"
    >
        <div class="flex min-h-6 items-center">
            <x-chief::input.checkbox
                data-table-row-checkbox
                name="{{ $this->getRowKey($item)  }}"
                id="{{ $this->getRowKey($item)  }}"
                x-on:change="toggleCheckbox('{{ $this->getRowKey($item) }}', $event.target.checked)"
            />
        </div>
    </td>

    <td class="py-1.5 pl-3 text-left">
        <img src="" />
        <h3><span>testje</span></h3>
        <p>dfqkjsdflmkqsjdf lmqskdjf lmqsdkfj qlmsdkfj qmsdlkfj qsmdfk</p>
    </td>
</tr>
