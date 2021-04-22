@if(\Illuminate\Support\Facades\Gate::check('update-page') || \Illuminate\Support\Facades\Gate::check('view-page') || \Illuminate\Support\Facades\Gate::check('view-squanto'))
    @php
        $hasActiveChildren = (isActiveUrl('admin/translations*') || isActiveUrl('admin/mediagallery*') || isActiveUrl('admin/menus*') || isActiveUrl('admin/sitemap*'));
    @endphp

    <div data-navigation-item class="space-y-4">
        <span
            data-navigation-item-label
            class="link link-black cursor-pointer {{ $hasActiveChildren ? 'active' : '' }}"
        >
            <x-icon-label space="large" icon="logo">Site</x-icon-label>
        </span>

        <div
            data-navigation-item-content
            class="flex flex-col space-y-3 animate-navigation-item-content-slide-in"
            style="margin-left: calc(20px + 1rem); {{ $hasActiveChildren ? '' : 'display: none;' }}"
        >
            @can('update-page')
                <a
                    class="link link-grey font-medium {{ isActiveUrl('admin/menus*') ? 'active' : '' }}"
                    href="{{ route('chief.back.menus.index') }}"
                    title="Menu"
                > Menu </a>
                <a
                    class="link link-grey font-medium {{ isActiveUrl('admin/mediagallery*') ? 'active' : '' }}"
                    href="{{ route('chief.mediagallery.index') }}"
                    title="Media"
                > Media </a>
            @endcan

            @can('view-squanto')
                <a
                    class="link link-grey font-medium {{ isActiveUrl('admin/translations*') ? 'active' : '' }}"
                    href="{{ route('squanto.index') }}"
                    title="Teksten"
                > Teksten </a>
            @endcan

            <a
                class="link link-grey font-medium {{ isActiveUrl('admin/sitemap*') ? 'active' : '' }}"
                href="{{ route('chief.back.sitemap.show') }}"
                title="Sitemap"
            > Sitemap </a>
        </div>
    </div>
@endif
