<options-dropdown class="inline-block">
    <div class="inset-s" v-cloak>

        @adminCan('preview', $model)
            <a class="block p-3 --link-with-bg" href="@adminRoute('preview', $model)" target="_blank">Bekijk op site</a>
        @endAdminCan

        @adminCan('edit', $model)
            <a href="@adminRoute('edit', $model)" class="block p-3 --link-with-bg">Aanpassen</a>
        @endAdminCan

        @foreach(['draft', 'publish', 'unpublish', 'archive', 'unarchive', 'delete'] as $action)
            @adminCan($action, $model)
                @include('chief::back.managers._transitions.'.$action)
            @endAdminCan
        @endforeach
    </div>
</options-dropdown>
