@push('portals')
    @adminCan('delete', $model)
        @include('chief::manager._transitions.modals.delete-modal')
    @endAdminCan

    @adminCan('archive', $model)
        @include('chief::manager._transitions.modals.archive-modal')
    @endAdminCan
@endpush

@push('custom-scripts-after-vue')
    @include('chief::layout._partials.editor-script')
@endpush

@include('chief::components.file-component')
@include('chief::components.filesupload-component')
