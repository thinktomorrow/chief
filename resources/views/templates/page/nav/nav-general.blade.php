@if(\Illuminate\Support\Facades\Gate::check('update-page') || \Illuminate\Support\Facades\Gate::check('view-page') || \Illuminate\Support\Facades\Gate::check('view-squanto'))
    @php
        $hasActiveChildren = (isActiveUrl('admin/translations*') || isActiveUrl('admin/mediagallery*') || isActiveUrl('admin/menus*') || isActiveUrl('admin/sitemap*'));
    @endphp

    @can('update-page')
        <x-chief::nav.item
            label="Menu"
            url="{{ route('chief.back.menus.index') }}"
            icon="<svg><use xlink:href='#icon-bars-4'></use></svg>"
            collapsible
        />

        <x-chief::nav.item
            label="Media"
            url="{{ route('chief.mediagallery.index') }}"
            icon="<svg><use xlink:href='#icon-photo'></use></svg>"
            collapsible
        />
    @endcan

    @can('view-squanto')
        <x-chief::nav.item
            label="Teksten"
            url="{{ route('squanto.index') }}"
            icon="<svg><use xlink:href='#icon-chat-bubble-bottom-center-text'></use></svg>"
            collapsible
        />
    @endcan
@endif
