@if(!$attributes->has('inline') && $items->count() > 1)
    <li>
        <dropdown>
            <span class="link link-black" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">{{ $title }}</span>

            <div v-cloak class="dropdown-box inset-s">
                @foreach($items as $navItem)
                    {!! $navItem->render() !!}
                @endforeach
            </div>
        </dropdown>
    </li>
@else
    @foreach($items as $navItem)
        <li>{!! $navItem->render() !!}</li>
    @endforeach
@endif
