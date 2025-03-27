@if (\Illuminate\Support\Facades\Gate::check('update-page') || \Illuminate\Support\Facades\Gate::check('view-page') || \Illuminate\Support\Facades\Gate::check('view-squanto'))
    @php
        $hasActiveChildren = isActiveUrl('admin/translations*') || isActiveUrl('admin/mediagallery*') || isActiveUrl('admin/menus*') || isActiveUrl('admin/sitemap*');
    @endphp

    @can('update-page')
        <x-chief::nav.item
            label="Menus"
            url="{{ route('chief.back.menus.index') }}"
            icon='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M4 4.5L20 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M4 14.5L20 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M4 9.5L20 9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M4 19.5L20 19.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
        />

        <x-chief::nav.item
            label="Media"
            url="{{ route('chief.mediagallery.index') }}"
            icon='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <circle cx="7.5" cy="7.5" r="1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M2.5 12C2.5 7.52166 2.5 5.28249 3.89124 3.89124C5.28249 2.5 7.52166 2.5 12 2.5C16.4783 2.5 18.7175 2.5 20.1088 3.89124C21.5 5.28249 21.5 7.52166 21.5 12C21.5 16.4783 21.5 18.7175 20.1088 20.1088C18.7175 21.5 16.4783 21.5 12 21.5C7.52166 21.5 5.28249 21.5 3.89124 20.1088C2.5 18.7175 2.5 16.4783 2.5 12Z" stroke="currentColor" stroke-width="1.5" /> <path d="M5 21C9.37246 15.775 14.2741 8.88406 21.4975 13.5424" stroke="currentColor" stroke-width="1.5" /> </svg>'
        />
    @endcan

    @can('view-squanto')
        <x-chief::nav.item
            label="Teksten"
            url="{{ route('squanto.index') }}"
            icon='<svg viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M20.5 16.9286V10C20.5 6.22876 20.5 4.34315 19.3284 3.17157C18.1569 2 16.2712 2 12.5 2H11.5C7.72876 2 5.84315 2 4.67157 3.17157C3.5 4.34315 3.5 6.22876 3.5 10V19.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /> <path d="M20.5 17H6C4.61929 17 3.5 18.1193 3.5 19.5C3.5 20.8807 4.61929 22 6 22H20.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /> <path d="M20.5 22C19.1193 22 18 20.8807 18 19.5C18 18.1193 19.1193 17 20.5 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" /> <path d="M15 7L9 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M12 11L9 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
        />
    @endcan
@endif
