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

<a data-submit-form="draftForm-{{ $model->getKey() }}" class="{{ $styleClasses }} cursor-pointer">Haal offline</a>

<form class="hidden" id="draftForm-{{ $model->getKey() }}" action="@adminRoute('unpublish', $model)" method="POST">
    {{ csrf_field() }}
</form>
