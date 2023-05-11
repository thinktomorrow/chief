@if(\Illuminate\Support\Facades\Gate::check('view-user') || \Illuminate\Support\Facades\Gate::check('view-role') || \Illuminate\Support\Facades\Gate::check('view-audit') || \Illuminate\Support\Facades\Gate::check('update-setting'))
    <x-chief::nav.item
        label="Instellingen"
        icon="<svg><use xlink:href='#icon-cog-8-tooth'></use></svg>"
        collapsible
    >
        @can('update-setting')
            <x-chief::nav.item label="Algemeen" url="{{ route('chief.back.settings.edit') }}" />
        @endcan

        @can('view-user')
            <x-chief::nav.item label="Admins" url="{{ route('chief.back.users.index') }}" />
        @endcan

        @can('view-role')
            <x-chief::nav.item label="Rechten" url="{{ route('chief.back.roles.index') }}" />
        @endcan

        <x-chief::nav.item label="Sitemap" url="{{ route('chief.back.sitemap.show') }}" />

        @can('view-audit')
            <x-chief::nav.item label="Audit" url="{{ route('chief.back.audit.index') }}" />
        @endcan
    </x-chief::nav.item>
@endif
