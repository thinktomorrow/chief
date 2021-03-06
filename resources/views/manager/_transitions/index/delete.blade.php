@push('portals')
    @include('chief::manager._transitions.modals.delete-modal')
@endpush

@php
    switch($style ?? null) {
        case 'button':
            $styleClasses = 'btn btn-error-outline'; break;
        case 'link':
            $styleClasses = 'link link-error'; break;
        case 'dropdown-link':
            $styleClasses = 'dropdown-link dropdown-link-error'; break;
        default:
            $styleClasses = 'btn btn-error-outline';
    }
@endphp

<a v-cloak @click="showModal('delete-manager-<?= $model->id; ?>')" class="{{ $styleClasses }} cursor-pointer">
    Verwijderen
</a>
