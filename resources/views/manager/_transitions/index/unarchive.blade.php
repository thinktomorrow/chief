@php
    switch($style ?? null) {
        case 'button':
            $styleClasses = 'btn btn-success'; break;
        case 'link':
            $styleClasses = 'link link-success'; break;
        case 'dropdown-link':
            $styleClasses = 'dropdown-link dropdown-link-success'; break;
        default:
            $styleClasses = 'btn btn-success';
    }
@endphp

<a form="unarchiveForm-{{ $model->id }}" class="{{ $styleClasses }} cursor-pointer">Herstellen</a>

<form class="hidden" id="unarchiveForm-{{ $model->id }}" action="@adminRoute('unarchive', $model)" method="POST">
    {{ csrf_field() }}
</form>
