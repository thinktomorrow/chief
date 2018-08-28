<li>
    <a class="nav-item icon icon-feather" href="{{ route('chief.back.dashboard') }}"></a>
</li>
<li>
    @foreach(\Thinktomorrow\Chief\Pages\Page::availableCollections()->filter(function($page, $key){ return $key == 'singles'; }) as $key => $collection)
        <a class="block nav-item  {{ isActiveUrl('admin/pages/'.$key.'*') ? 'active' : '' }}" href="{{ route('chief.back.pages.index',['collection' => $key]) }}">{{ ucfirst($collection->plural) }}</a>
    @endforeach
</li>

<li>
    <dropdown>
        <span class="center-y nav-item {{ (isActiveUrl('admin/pages*') && !isActiveUrl('admin/pages/singles*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">Collecties</span>
        <div v-cloak class="dropdown-box inset-s">
            @foreach(\Thinktomorrow\Chief\Pages\Page::availableCollections()->reject(function($page, $key){ return $key == 'singles'; }) as $key => $collection)
                <a class="block squished --link-with-bg {{ isActiveUrl('admin/pages/'.$key.'*') ? 'active' : '' }}" href="{{ route('chief.back.pages.index',['collection' => $key]) }}">{{ ucfirst($collection->plural) }}</a>
            @endforeach
        </div>
    </dropdown>
</li>
<li>
    <dropdown>
        <span class="center-y nav-item {{ (isActiveUrl('admin/translations*') || isActiveUrl('admin/menu*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">Site</span>
        <div v-cloak class="dropdown-box inset-s">
            <a class="block squished --link-with-bg {{ isActiveUrl('admin/menus*') ? 'active' : '' }}" href="{{ route('chief.back.menus.index') }}">Menu</a>
            <a class="block squished --link-with-bg {{ isActiveUrl('admin/modules*') ? 'active' : '' }}" href="{{ route('chief.back.modules.index') }}">Vaste modules</a>
            <a class="block squished --link-with-bg {{ isActiveUrl('admin/translations*') ? 'active' : '' }}" href="{{ route('squanto.index') }}">Teksten</a>
        </div>
    </dropdown>
</li>