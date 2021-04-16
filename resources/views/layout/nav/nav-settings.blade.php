@if(\Illuminate\Support\Facades\Gate::check('view-user') || \Illuminate\Support\Facades\Gate::check('view-role') || \Illuminate\Support\Facades\Gate::check('view-audit') || \Illuminate\Support\Facades\Gate::check('update-setting'))
    <dropdown>
        <span
            class="{{ (isActiveUrl('admin/users*') || isActiveUrl('admin/settings*') || isActiveUrl('admin/audit*')) ? 'link link-black active' : 'link link-black' }}"
            slot="trigger"
            slot-scope="{ toggle, isActive }"
            @click="toggle"
        >
            <x-link-label type="settings" space="large">Instellingen</x-link-label>
        </span>

        <div v-cloak class="dropdown-content">
            @can('view-user')
                <a class="{{ isActiveUrl('admin/users*') ? 'dropdown-link active' : 'dropdown-link' }}" href="{{ route('chief.back.users.index') }}">Admins</a>
            @endcan

            @can('view-role')
                <a class="{{ isActiveUrl('admin/roles*') ? 'dropdown-link active' : 'dropdown-link' }}" href="{{ route('chief.back.roles.index') }}">Rechten</a>
            @endcan

            @can('update-setting')
                <a class="{{ isActiveUrl('admin/settings*') ? 'dropdown-link active' : 'dropdown-link' }}" href="{{ route('chief.back.settings.edit') }}">Settings</a>
            @endcan

            @can('view-audit')
                <a class="{{ isActiveUrl('admin/audit*') ? 'dropdown-link active' : 'dropdown-link' }}" href="{{ route('chief.back.audit.index') }}">Audit</a>
            @endcan
        </div>
    </dropdown>
@endif
