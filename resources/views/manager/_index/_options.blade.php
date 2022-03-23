<options-dropdown class="link link-primary">
    <div v-cloak class="dropdown-content">
        @adminCan('preview', $model)
            <a href="@adminRoute('preview', $model)" target="_blank" class="dropdown-link">
                Bekijk op site
            </a>
        @endAdminCan

        @adminCan('edit', $model)
            <a href="@adminRoute('edit', $model)" class="dropdown-link">
                Aanpassen
            </a>
        @endAdminCan

        @foreach(['draft', 'publish', 'unpublish', 'unarchive'] as $action)
            @adminCan($action, $model)
                @include('chief::manager._transitions.index.'. $action, [ 'style' => 'dropdown-link' ])
            @endAdminCan
        @endforeach

        @adminCan('duplicate', $model)
            @include('chief::manager._transitions.index.duplicate')
        @endAdminCan
    </div>
</options-dropdown>
