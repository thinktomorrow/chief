<nav class="bg-white border-b border-secondary-200">
    <div class="container">
        <div class="stack-xs row justify-between">

            <ul class="navigation-list flex items-center">

                <li>
                    <a href="{{ route('chief.back.dashboard') }}">
                        <svg width="18" height="18"><use xlink:href="#logo"/></svg>
                    </a>
                </li>

                @include('chief::back._layouts._partials.nav-main')

                @if(\Illuminate\Support\Facades\Gate::check('update-page') || \Illuminate\Support\Facades\Gate::check('view-page') || \Illuminate\Support\Facades\Gate::check('view-squanto'))
                    <li>
                        <dropdown>
                            <span class="center-y {{ (isActiveUrl('admin/translations*') || isActiveUrl('admin/menu*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">Site</span>
                            <div v-cloak class="dropdown-box inset-s">
                                @can('update-page')
                                    <a class="{{ isActiveUrl('admin/menus*') ? 'active' : '' }}" href="{{ route('chief.back.menus.index') }}">Menu</a>
                                @endcan
                                @can('view-page')
                                    @if(\Thinktomorrow\Chief\Modules\Module::atLeastOneRegistered())
                                        <a class="{{ isActiveUrl('admin/modules*') ? 'active' : '' }}" href="{{ route('chief.back.modules.index') }}">Vaste modules</a>
                                    @endif
                                @endcan
                                @can('view-squanto')
                                    <a class="{{ isActiveUrl('admin/translations*') ? 'active' : '' }}" href="{{ route('squanto.index') }}">Teksten</a>
                                @endcan

                                <a class="{{ isActiveUrl('admin/sitemap*') ? 'active' : '' }}" href="{{ route('chief.back.sitemap.show') }}">Sitemap</a>
                            </div>
                        </dropdown>
                    </li>
                @endif
            </ul>

            <ul class="navigation-list flex float-right items-center">
                {{-- @role('developer')
                    <li class="no-hover px-6"><a class="label label-primary" target="_blank" href="/spirit">Spirit</a></li>
                @endrole --}}
                @if(\Illuminate\Support\Facades\Gate::check('view-user') || \Illuminate\Support\Facades\Gate::check('view-role') || \Illuminate\Support\Facades\Gate::check('view-audit') || \Illuminate\Support\Facades\Gate::check('update-setting'))
                    <li>
                        <dropdown>
                            <span class="center-y nav-item {{ (isActiveUrl('admin/users*') || isActiveUrl('admin/settings*') || isActiveUrl('admin/audit*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">
                                <svg width="18" height="18"><use xlink:href="#settings"/></svg>
                            </span>
                            <div v-cloak class="dropdown-box inset-s">
                                @can('view-user')
                                    <a class="{{ isActiveUrl('admin/users*') ? 'active' : '' }}" href="{{ route('chief.back.users.index') }}">Users</a>
                                @endcan
                                @can('view-role')
                                    <a class="{{ isActiveUrl('admin/roles*') ? 'active' : '' }}" href="{{ route('chief.back.roles.index') }}">Rechten</a>
                                @endcan
                                @can('update-setting')
                                    <a class="{{ isActiveUrl('admin/settings*') ? 'active' : '' }}" href="{{ route('chief.back.settings.edit') }}">Settings</a>
                                @endcan
                                @can('view-audit')
                                    <a class="{{ isActiveUrl('admin/audit*') ? 'active' : '' }}" href="{{ route('chief.back.audit.index') }}">Audit</a>
                                @endcan
                            </div>
                        </dropdown>
                    </li>
                @endif

                <li>
                    <dropdown>
                        <span class="nav-item" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">{{ chiefAdmin()->firstname }}</span>
                        <div v-cloak class="dropdown-box inset-s">
                            @can('update-you')
                                <a href="{{ route('chief.back.you.edit') }}">Wijzig profiel</a>
                            @endcan
                            <a href="{{ route('chief.back.password.edit') }}">Wijzig wachtwoord</a>
                            <a href="{{ route('chief.back.logout') }}">Log out</a>
                        </div>
                    </dropdown>
                </li>
            </ul>

        </div>
    </div>
</nav>
