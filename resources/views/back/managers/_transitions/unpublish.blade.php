<a data-submit-form="draftForm-{{ $model->id }}" class="block p-3 text-warning --link-with-bg">Haal offline</a>

<form class="hidden" id="draftForm-{{ $model->id }}" action="@adminRoute('unpublish', $model)" method="POST">
    {{ csrf_field() }}
    <button type="submit">Unpublish</button>
</form>
