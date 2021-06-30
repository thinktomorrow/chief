<a form="duplicateForm-{{ $model->id }}" class="dropdown-link dropdown-link-success cursor-pointer">Kopieer</a>

<form class="hidden" id="duplicateForm-{{ $model->id }}" action="@adminRoute('duplicate', $model)" method="POST">
    {{ csrf_field() }}
</form>
