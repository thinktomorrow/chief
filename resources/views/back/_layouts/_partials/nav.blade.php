<nav class="top-nav bg-white">
    <div class="container">
        <div class="row">
            <ul id="nav-main" class="nav-items">
                <li>
                    <a class="nav-item icon icon-feather" href="{{ route('chief.back.dashboard') }}"></a>
                </li>
                <li>
                    <dropdown>
                        <span class="center-y nav-item {{ isActiveUrl('admin/pages*') ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle"><span class="inline-s">Pagina's</span><span class="icon icon-chevron-down"></span></span>
                        <div v-cloak class="dropdown-box inset-s">
                            @foreach(\Thinktomorrow\Chief\Pages\Page::availableCollections() as $key => $collection)
                                <a class="block squished --link-with-bg {{ isActiveUrl('admin/pages/'.$key.'*') ? 'active' : '' }}" href="{{ route('chief.back.pages.index',['collection' => $key]) }}">{{ $collection->plural }}</a>
                            @endforeach
                        </div>
                    </dropdown>
                </li>
                <li><a class="nav-item {{ isActiveUrl('admin/modules*') ? 'active' : '' }}" href="{{ route('chief.back.modules.index') }}">Modules</a></li>
                <dropdown>
                    <span class="center-y nav-item {{ (isActiveUrl('admin/translations*') || isActiveUrl('admin/menu*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">Site</span>
                    <div v-cloak class="dropdown-box inset-s">
                        <a class="block squished --link-with-bg {{ isActiveUrl('admin/menu*') ? 'active' : '' }}" href="{{ route('chief.back.menu.index') }}">Menu</a>
                        <a class="block squished --link-with-bg {{ isActiveUrl('admin/translations*') ? 'active' : '' }}" href="{{ route('squanto.index') }}">Teksten</a>
                    </div>
                </dropdown>
            </ul>

            <div class="column">
                <ul class="nav-items right">
                    <li><a class="label label--primary squished-xs" target="_blank" href="/spirit">Spirit</a></li>

                    <dropdown>
                        <span class="center-y nav-item {{ (isActiveUrl('admin/users*') || isActiveUrl('admin/settings*') || isActiveUrl('admin/audit*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle"><span class="icon icon-cog"></span></span>
                        <div v-cloak class="dropdown-box inset-s">
                            @can('view-user')
                                <a class="block squished --link-with-bg {{ isActiveUrl('admin/users*') ? 'active' : '' }}" href="{{ route('chief.back.users.index') }}">Users</a>
                            @endcan
                            <a class="block squished --link-with-bg {{ isActiveUrl('admin/settings*') ? 'active' : '' }}" href="{{ route('chief.back.settings.index') }}">Settings</a>
                            @can('view-audit')
                                <a class="block squished --link-with-bg {{ isActiveUrl('admin/audit*') ? 'active' : '' }}" href="{{ route('chief.back.audit.index') }}">Audit</a>
                            @endcan
                        </div>
                    </dropdown>

                    <li>
                        <dropdown>
                            <span class="nav-item" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">{{ admin()->firstname }}</span>
                            <div v-cloak class="dropdown-box">
                                <a class="block squished-s --link-with-bg" href="{{ route('chief.back.you.edit') }}">Wijzig profiel</a>
                                <a class="block squished-s --link-with-bg" href="{{ route('chief.back.password.edit') }}">Wijzig wachtwoord</a>
                                <a class="block squished-s --link-with-bg" href="{{ route('chief.back.logout') }}">Log out</a>
                            </div>
                        </dropdown>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
