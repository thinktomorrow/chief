@if(!$attributes->has('inline') && $items->count() > 1)
    @php
        $icon = (($firstItem = $items->first()) && $firstItem->icon())
            ? $firstItem->icon() : '<svg><use xlink:href="#icon-rectangle-stack"></use></svg>';

        $isActive = false;
        foreach($items as $navItem) {
            if(isActiveUrl($navItem->url()) || isActiveUrl($navItem->url() .'/*')) {
                $isActive = true;
                $showOpenDropdown = true;
            }
        }
    @endphp

    <x-chief::nav.item
        label="{{ $title }}"
        icon="{!! $icon !!}"
        collapsible
        {{ $attributes }}
    >
        @foreach($items as $navItem)
            <x-chief::nav.item
                label="{{ ucfirst($navItem->label()) }}"
                url="{{ $navItem->url() }}"
            />
        @endforeach
    </x-chief::nav.item>
@else
    @foreach($items as $navItem)

        @if($loop->first && $title)
            <div class="text-sm tracking-wider text-grey-500 uppercase" style="padding: 0 0.5rem; margin-bottom: 1rem;">
                {{ $title }}
            </div>
        @endif

        <x-chief::nav.item
            label="{{ ucfirst($navItem->label()) }}"
            url="{{ $navItem->url() }}"
            icon="{!! $navItem->icon() !!}"
            collapsible
            {{ $attributes }}
        />
    @endforeach
@endif
