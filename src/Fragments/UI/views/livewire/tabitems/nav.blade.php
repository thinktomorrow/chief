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

            @foreach($item->getAllowedSites() as $site)
                <x-chief::badge
                    variant="{{ $site == $this->scopedLocale ? 'blue' : (in_array($site, $item->getActiveSites()) ? 'grey' : 'outline-transparent') }}"
                    size="xs">{{ count($locales) > 1 ? \Thinktomorrow\Chief\Sites\ChiefSites::all()->find($site)->shortName : 'live' }}</x-chief::badge>
            @endforeach
        </x-chief::window.tabs.item>
    @endforeach

    <x-chief::window.tabs.item wire:click="addItem">
        <x-chief::icon.plus-sign class="size-5" />
    </x-chief::window.tabs.item>
</x-chief::window.tabs>
