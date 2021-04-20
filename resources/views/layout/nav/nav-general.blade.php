@if(\Illuminate\Support\Facades\Gate::check('update-page') || \Illuminate\Support\Facades\Gate::check('view-page') || \Illuminate\Support\Facades\Gate::check('view-squanto'))
    <div class="space-y-4">
        <span
            id="test-nav-link"
            class="link link-black {{ (isActiveUrl('admin/translations*') || isActiveUrl('admin/mediagallery*') || isActiveUrl('admin/menus*') || isActiveUrl('admin/sitemap*')) ? 'active' : '' }}"
            style="margin-left: calc(-20px - 1rem)"
        >
            <x-icon-label space="large" icon="logo">Site</x-icon-label>
        </span>

        <div class="flex flex-col space-y-3" style="display:none" id="sub-nav-links">
            @can('update-page')
                <a class="link link-black text-grey-700 font-medium {{ isActiveUrl('admin/menus*') ? 'active' : '' }}" href="{{ route('chief.back.menus.index') }}">Menu</a>
                <a class="link link-black text-grey-700 font-medium {{ isActiveUrl('admin/mediagallery*') ? 'active' : '' }}" href="{{ route('chief.mediagallery.index') }}">Media</a>
            @endcan

            @can('view-squanto')
                <a class="link link-black text-grey-700 font-medium {{ isActiveUrl('admin/translations*') ? 'active' : '' }}" href="{{ route('squanto.index') }}">Teksten</a>
            @endcan

            <a class="link link-black text-grey-700 font-medium {{ isActiveUrl('admin/sitemap*') ? 'active' : '' }}" href="{{ route('chief.back.sitemap.show') }}">Sitemap</a>
        </div>
    </div>
@endif

{{-- TODO: make this dynamic (and less hurtful for my soul) --}}
@push('custom-scripts-after-vue')
    <script>
        const navLink = document.getElementById('test-nav-link');
        let isOpen = false;

        navLink.addEventListener('click', function() {
            if(isOpen) {
                document.getElementById('sub-nav-links').style.display = "none";
            } else {
                document.getElementById('sub-nav-links').style.display = "flex";
            }
            isOpen = !isOpen;
        });
    </script>
@endpush

{{-- @if(\Illuminate\Support\Facades\Gate::check('update-page') || \Illuminate\Support\Facades\Gate::check('view-page') || \Illuminate\Support\Facades\Gate::check('view-squanto'))
    <dropdown>
        <span class="link link-black {{ (isActiveUrl('admin/translations*') || isActiveUrl('admin/mediagallery*') || isActiveUrl('admin/menus*') || isActiveUrl('admin/sitemap*')) ? 'active' : '' }}" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">Site</span>

        <div v-cloak class="dropdown-content">
            @can('update-page')
                <a class="dropdown-link {{ isActiveUrl('admin/menus*') ? 'active' : '' }}" href="{{ route('chief.back.menus.index') }}">Menu</a>
                <a class="dropdown-link {{ isActiveUrl('admin/mediagallery*') ? 'active' : '' }}" href="{{ route('chief.mediagallery.index') }}">Media</a>
            @endcan

            @can('view-squanto')
                <a class="dropdown-link {{ isActiveUrl('admin/translations*') ? 'active' : '' }}" href="{{ route('squanto.index') }}">Teksten</a>
            @endcan

            <a class="dropdown-link {{ isActiveUrl('admin/sitemap*') ? 'active' : '' }}" href="{{ route('chief.back.sitemap.show') }}">Sitemap</a>
        </div>
    </dropdown>
@endif --}}
