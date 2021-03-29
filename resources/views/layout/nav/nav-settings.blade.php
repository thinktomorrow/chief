@if(\Illuminate\Support\Facades\Gate::check('view-user') || \Illuminate\Support\Facades\Gate::check('view-role') || \Illuminate\Support\Facades\Gate::check('view-audit') || \Illuminate\Support\Facades\Gate::check('update-setting'))
    <dropdown>
        <span class="link link-black {{ (isActiveUrl('admin/users*') || isActiveUrl('admin/settings*') || isActiveUrl('admin/audit*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">
            <svg width="18" height="18"><use xlink:href="#settings"/></svg>
        </span>

        <div v-cloak class="dropdown-content">
            @can('view-user')
                <a class="dropdown-link {{ isActiveUrl('admin/users*') ? 'active' : '' }}" href="{{ route('chief.back.users.index') }}">Admins</a>
            @endcan
            @can('view-role')
                <a class="dropdown-link {{ isActiveUrl('admin/roles*') ? 'active' : '' }}" href="{{ route('chief.back.roles.index') }}">Rechten</a>
            @endcan
            @can('update-setting')
                <a class="dropdown-link {{ isActiveUrl('admin/settings*') ? 'active' : '' }}" href="{{ route('chief.back.settings.edit') }}">Settings</a>
            @endcan
            @can('view-audit')
                <a class="dropdown-link {{ isActiveUrl('admin/audit*') ? 'active' : '' }}" href="{{ route('chief.back.audit.index') }}">Audit</a>
            @endcan
        </div>
    </dropdown>
@endif

<dropdown>
    <span class="link link-black" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">{{ chiefAdmin()->firstname }}</span>

    <div v-cloak class="dropdown-content ">
        @can('update-you')
            <a class="dropdown-link" href="{{ route('chief.back.you.edit') }}">Wijzig profiel</a>
        @endcan

        <a class="dropdown-link" href="{{ route('chief.back.password.edit') }}">Wijzig wachtwoord</a>
        <a class="dropdown-link" href="{{ route('chief.back.logout') }}">Log out</a>
    </div>
</dropdown>
