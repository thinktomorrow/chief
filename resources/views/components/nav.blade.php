@if(!$attributes->has('inline') && $items->count() > 1)
    @php
        $showOpenDropdown = $attributes->has('open');
        $isActive = false;

        foreach($items as $navItem) {
            if(isActiveUrl($navItem->url())) {
                $isActive = true;
            }
        }

        $groupIcon = (($firstItem = $items->first()) && $firstItem->icon()) ? $firstItem->icon() : 'icon-folder';

    @endphp

    <div data-navigation-item class="space-y-4">
        <span
            data-navigation-item-label
            class="link link-black cursor-pointer {{ $isActive ? 'active' : '' }}"
        >
            <x-icon-label space="large" icon="{!! $groupIcon !!}">{{ $title }}</x-icon-label>
        </span>

        <div
            data-navigation-item-content
            class="flex flex-col space-y-3 animate-navigation-item-content-slide-in"
            style="margin-left: calc(20px + 1rem); {{ $showOpenDropdown ? '' : 'display: none;' }}"
        >
            @foreach($items as $navItem)
                <a
                    class="link link-grey font-medium {{ isActiveUrl($navItem->url()) ? 'active' : '' }}"
                    href="{{ $navItem->url() }}"
                    title="{{ ucfirst($navItem->label()) }}"
                > {{ ucfirst($navItem->label()) }} </a>
            @endforeach
        </div>
    </div>
@else
    @foreach($items as $navItem)
        <a
            href="{{ $navItem->url() }}"
            class="{{ isActiveUrl($navItem->url()) ? 'link link-black active' : 'link link-black' }}"
        >
            <x-icon-label space="large" icon="{!! $navItem->icon()  !!}">{{ ucfirst($navItem->label()) }}</x-icon-label>
        </a>
    @endforeach
@endif
