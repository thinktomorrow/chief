@php
    switch($style ?? null) {
        case 'button':
            $styleClasses = 'btn btn-grey'; break;
        case 'link':
            $styleClasses = 'link link-grey'; break;
        case 'dropdown-link':
            $styleClasses = 'dropdown-link dropdown-link-grey'; break;
        default:
            $styleClasses = 'btn btn-grey';
    }
@endphp

<a form="draftForm-{{ $model->id }}" class="{{ $styleClasses }} cursor-pointer">Haal offline</a>

<form class="hidden" id="draftForm-{{ $model->id }}" action="@adminRoute('unpublish', $model)" method="POST">
    {{ csrf_field() }}
</form>
