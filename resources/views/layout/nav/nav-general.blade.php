@if(\Illuminate\Support\Facades\Gate::check('update-page') || \Illuminate\Support\Facades\Gate::check('view-page') || \Illuminate\Support\Facades\Gate::check('view-squanto'))
    @php
        $hasActiveChildren = (isActiveUrl('admin/translations*') || isActiveUrl('admin/mediagallery*') || isActiveUrl('admin/menus*') || isActiveUrl('admin/sitemap*'));
    @endphp

    <x-chief::nav.item label="Site" icon="<svg><use xlink:href='#logo'></use></svg>" collapsible>
        @can('update-page')
            <x-chief::nav.item label="Menu" url="{{ route('chief.back.menus.index') }}" />
            <x-chief::nav.item label="Media" url="{{ route('chief.mediagallery.index') }}" />
        @endcan

        @can('view-squanto')
            <x-chief::nav.item label="Teksten" url="{{ route('squanto.index') }}" />
        @endcan

        <x-chief::nav.item label="Sitemap" url="{{ route('chief.back.sitemap.show') }}" />
    </x-chief::nav.item>
@endif
