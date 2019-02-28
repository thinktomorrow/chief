<nav class="top-nav bg-white">
    <div class="container">
        <div class="row">
            <ul id="nav-main" class="nav-items">
                <li>
                    <a class="nav-item icon icon-feather" href="{{ route('chief.back.dashboard') }}"></a>
                </li>

                @include('chief::back._layouts._partials.nav-main')


                @if(\Illuminate\Support\Facades\Gate::check('update-page') || \Illuminate\Support\Facades\Gate::check('view-page') || \Illuminate\Support\Facades\Gate::check('view-squanto'))
                    <li>
                        <dropdown>
                            <span class="center-y nav-item {{ (isActiveUrl('admin/translations*') || isActiveUrl('admin/menu*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">Site</span>
                            <div v-cloak class="dropdown-box inset-s">
                                @can('update-page')
                                    <a class="block squished --link-with-bg {{ isActiveUrl('admin/menus*') ? 'active' : '' }}" href="{{ route('chief.back.menus.index') }}">Menu</a>
                                @endcan
                                @can('view-page')
                                    <a class="block squished --link-with-bg {{ isActiveUrl('admin/modules*') ? 'active' : '' }}" href="{{ route('chief.back.modules.index') }}">Vaste modules</a>
                                @endcan
                                @can('view-squanto')
                                    <a class="block squished --link-with-bg {{ isActiveUrl('admin/translations*') ? 'active' : '' }}" href="{{ route('squanto.index') }}">Teksten</a>
                                @endcan
                            </div>
                        </dropdown>
                    </li>
                @endif
            </ul>

            <div class="column">
                <ul class="nav-items right">
                    @role('developer')
                        <li><a class="label label--primary squished-xs" target="_blank" href="/spirit">Spirit</a></li>
                    @endrole
                    @if(\Illuminate\Support\Facades\Gate::check('update-user') || \Illuminate\Support\Facades\Gate::check('update-setting'))
                        <dropdown>
                            <span class="center-y nav-item {{ (isActiveUrl('admin/users*') || isActiveUrl('admin/settings*') || isActiveUrl('admin/audit*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle"><span class="icon icon-cog"></span></span>
                            <div v-cloak class="dropdown-box inset-s">
                                @can('view-user')
                                    <a class="block squished --link-with-bg {{ isActiveUrl('admin/users*') ? 'active' : '' }}" href="{{ route('chief.back.users.index') }}">Users</a>
                                @endcan
                                    <a class="block squished --link-with-bg {{ isActiveUrl('admin/settings*') ? 'active' : '' }}" href="{{ route('chief.back.settings.edit') }}">Settings</a>
                                @can('view-audit')
                                    <a class="block squished --link-with-bg {{ isActiveUrl('admin/audit*') ? 'active' : '' }}" href="{{ route('chief.back.audit.index') }}">Audit</a>
                                @endcan
                            </div>
                        </dropdown>
                    @endif

                    <li>
                        <dropdown>
                            <span class="nav-item" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">{{ chiefAdmin()->firstname }}</span>
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
