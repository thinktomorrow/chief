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

<a data-submit-form="publishForm-{{ $model->id }}" class="{{ $styleClasses }} cursor-pointer">Zet online</a>

<form class="hidden" id="publishForm-{{ $model->id }}" action="@adminRoute('publish', $model)" method="POST">
    {{ csrf_field() }}
</form>
