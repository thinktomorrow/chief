{{-- @if(!$attributes->has('inline') && $items->count() > 1)
    <dropdown>
        <span class="link link-black" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">{{ $title }}</span>

        <div v-cloak class="dropdown-content">
            @foreach($items as $navItem)
                <a
                    class="{{ isActiveUrl($navItem->url()) ? 'dropdown-link active' : 'dropdown-link' }}"
                    href="{{ $navItem->url() }}"
                >
                    {{ ucfirst($navItem->label()) }}
                </a>
            @endforeach
        </div>
    </dropdown>
@else
    @foreach($items as $navItem)
        <a
            class="{{ isActiveUrl($navItem->url()) ? 'link link-black active' : 'link link-black' }}"
            href="{{ $navItem->url() }}"
        >
            {{ ucfirst($navItem->label()) }}
        </a>
    @endforeach
@endif --}}

@if(!$attributes->has('inline') && $items->count() > 1)
    <span class="link link-black" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">{{ $title }}</span>

    @foreach($items as $navItem)
        <a
            class="{{ isActiveUrl($navItem->url()) ? 'link link-black active' : 'link link-black' }}"
            href="{{ $navItem->url() }}"
        >
            {{ ucfirst($navItem->label()) }}
        </a>
    @endforeach
@else
    @foreach($items as $navItem)
        <a
            href="{{ $navItem->url() }}"
            class="{{ isActiveUrl($navItem->url()) ? 'link link-black active' : 'link link-black' }}"
            style="margin-left: calc(-20px - 1rem)"
        >
            <x-icon-label space="large" icon="icon-folder">{{ ucfirst($navItem->label()) }}</x-icon-label>
        </a>
    @endforeach
@endif
