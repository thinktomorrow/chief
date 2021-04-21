<a data-submit-form="draftForm-{{ $model->id }}" class="dropdown-link hover:bg-grey-50 hover:text-grey-400 cursor-pointer">Haal offline</a>

<form class="hidden" id="draftForm-{{ $model->id }}" action="@adminRoute('unpublish', $model)" method="POST">
    {{ csrf_field() }}
    <button type="submit">Unpublish</button>
</form>
