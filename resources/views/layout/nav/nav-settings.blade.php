@if(\Illuminate\Support\Facades\Gate::check('view-user') || \Illuminate\Support\Facades\Gate::check('view-role') || \Illuminate\Support\Facades\Gate::check('view-audit') || \Illuminate\Support\Facades\Gate::check('update-setting'))
    @php
        $hasActiveChildren = (isActiveUrl('admin/users*') || isActiveUrl('admin/settings*') || isActiveUrl('admin/audit*') || isActiveUrl('admin/roles*'));
    @endphp

    <x-chief::nav.item
        label="Instellingen"
        icon="<svg><use xlink:href='#icon-settings'></use></svg>"
        collapsible
    >
        @can('view-user')
            <x-chief::nav.item label="Admins" url="{{ route('chief.back.users.index') }}" />
        @endcan

        @can('view-role')
            <x-chief::nav.item label="Rechten" url="{{ route('chief.back.roles.index') }}" />
        @endcan

        @can('update-setting')
            <x-chief::nav.item label="Settings" url="{{ route('chief.back.settings.edit') }}" />
        @endcan

        @can('view-audit')
            <x-chief::nav.item label="Audit" url="{{ route('chief.back.audit.index') }}" />
        @endcan
    </x-chief::nav.item>
@endif
