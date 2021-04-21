@push('portals')
    @include('chief::manager._transitions.modals.archive-modal')
@endpush

@php
    switch($style ?? null) {
        case 'button':
            $styleClasses = 'btn btn-warning'; break;
        case 'link':
            $styleClasses = 'link link-warning'; break;
        case 'dropdown-link':
            $styleClasses = 'dropdown-link dropdown-link-warning'; break;
        default:
            $styleClasses = 'btn btn-warning';
    }
@endphp

<a v-cloak @click="showModal('archive-manager-<?= $model->id ?>')" class="{{ $styleClasses }} cursor-pointer">
    Archiveer deze pagina
</a>
