<a data-submit-form="unarchiveForm-{{ $model->id }}" class="block p-3 text-warning --link-with-bg">Herstel</a>

<form class="hidden" id="unarchiveForm-{{ $model->id }}" action="@adminRoute('unarchive', $model)" method="POST">
    {{ csrf_field() }}
    <button type="submit">Herstel</button>
</form>
