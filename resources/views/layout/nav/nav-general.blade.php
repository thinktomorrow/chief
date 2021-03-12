@if(\Illuminate\Support\Facades\Gate::check('update-page') || \Illuminate\Support\Facades\Gate::check('view-page') || \Illuminate\Support\Facades\Gate::check('view-squanto'))
    <li>
        <dropdown>
            <span class="link link-black {{ (isActiveUrl('admin/translations*') || isActiveUrl('admin/mediagallery*') || isActiveUrl('admin/menus*') || isActiveUrl('admin/sitemap*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">Site</span>

            <div v-cloak class="dropdown-box inset-s">
                @can('update-page')
                    <a class="{{ isActiveUrl('admin/menus*') ? 'active' : '' }}" href="{{ route('chief.back.menus.index') }}">Menu</a>
                    <a class="{{ isActiveUrl('admin/mediagallery*') ? 'active' : '' }}" href="{{ route('chief.mediagallery.index') }}">Media</a>
                @endcan
                @can('view-squanto')
                    <a class="{{ isActiveUrl('admin/translations*') ? 'active' : '' }}" href="{{ route('squanto.index') }}">Teksten</a>
                @endcan

                <a class="{{ isActiveUrl('admin/sitemap*') ? 'active' : '' }}" href="{{ route('chief.back.sitemap.show') }}">Sitemap</a>
            </div>
        </dropdown>
    </li>
@endif
