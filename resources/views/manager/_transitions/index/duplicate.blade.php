<a data-submit-form="duplicateForm-{{ $model->getKey() }}" class="dropdown-link dropdown-link-success cursor-pointer">Kopieer</a>

<form class="hidden" id="duplicateForm-{{ $model->getKey() }}" action="@adminRoute('duplicate', $model)" method="POST">
    {{ csrf_field() }}
</form>
