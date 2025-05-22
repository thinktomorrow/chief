<x-chief::window.tabs>
    @foreach ($items as $i => $item)
        <x-chief::window.tabs.item
            aria-controls="{{ $item->getId() }}"
            aria-selected="{{ $item->getId() === $activeItemId }}"
            wire:key="tabbed-item-{{ $item->getId() }}"
            wire:click.prevent="showItem('{{ $item->getId() }}')"
            :active="$item->getId() === $activeItemId"
        >
            {{ $item->getTitle() }}
        </x-chief::window.tabs.item>
    @endforeach

    @if($this->allowMultipleItems())
        <x-chief::window.tabs.item wire:click="addItem">
            <x-chief::icon.plus-sign class="size-5" />
        </x-chief::window.tabs.item>
    @endif
</x-chief::window.tabs>
