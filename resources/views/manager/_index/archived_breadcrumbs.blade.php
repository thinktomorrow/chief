@if(isset($is_archive_index) && $is_archive_index)
    @slot('breadcrumbs')
        @adminCan('index')
        <a href="@adminRoute('index')" class="link link-primary">
            <x-chief-icon-label type="back">Terug naar overzicht</x-chief-icon-label>
        </a>
        @endAdminCan
    @endslot
@endif
