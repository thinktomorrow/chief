<options-dropdown>
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

        @foreach(['draft', 'publish', 'unpublish', 'archive', 'unarchive', 'delete'] as $action)
            @adminCan($action, $model)
                @include('chief::back.managers._transitions.'.$action)
            @endAdminCan
        @endforeach
    </div>
</options-dropdown>
