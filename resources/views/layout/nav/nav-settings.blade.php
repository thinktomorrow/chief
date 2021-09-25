@if(\Illuminate\Support\Facades\Gate::check('view-user') || \Illuminate\Support\Facades\Gate::check('view-role') || \Illuminate\Support\Facades\Gate::check('view-audit') || \Illuminate\Support\Facades\Gate::check('update-setting'))
    @php
        $hasActiveChildren = (isActiveUrl('admin/users*') || isActiveUrl('admin/settings*') || isActiveUrl('admin/audit*') || isActiveUrl('admin/roles*'));
    @endphp

    <div data-navigation-item class="space-y-4">
        <span
            data-navigation-item-label
            class="link link-black cursor-pointer {{ $hasActiveChildren ? 'active' : '' }}"
        >
            <x-chief-icon-label space="large" icon="icon-settings">Instellingen</x-chief-icon-label>
        </span>

        <div
            data-navigation-item-content
            class="flex flex-col space-y-3 animate-navigation-item-content-slide-in"
            style="margin-left: calc(20px + 1rem); {{ $hasActiveChildren ? '' : 'display: none;' }}"
        >
            @can('view-user')
                <a
                    class="link link-grey font-medium {{ isActiveUrl('admin/users*') ? 'active' : '' }}"
                    href="{{ route('chief.back.users.index') }}"
                    title="Admins"
                > Admins </a>
            @endcan

            @can('view-role')
                <a
                    class="link link-grey font-medium {{ isActiveUrl('admin/roles*') ? 'active' : '' }}"
                    href="{{ route('chief.back.roles.index') }}"
                    title="Rechten"
                > Rechten </a>
            @endcan

            @can('update-setting')
                <a
                    class="link link-grey font-medium {{ isActiveUrl('admin/settings*') ? 'active' : '' }}"
                    href="{{ route('chief.back.settings.edit') }}"
                    title="Settings"
                > Settings </a>
            @endcan

            @can('view-audit')
                <a
                    class="link link-grey font-medium {{ isActiveUrl('admin/audit*') ? 'active' : '' }}"
                    href="{{ route('chief.back.audit.index') }}"
                    title="Audit"
                > Audit </a>
            @endcan
        </div>
    </div>
@endif
