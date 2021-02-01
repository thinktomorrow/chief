<a data-submit-form="publishForm-{{ $model->id }}" class="block p-3 --link-with-bg">Zet online</a>

<form class="hidden" id="publishForm-{{ $model->id }}" action="@adminRoute('publish', $model)" method="POST">
    {{ csrf_field() }}
    <button type="submit">Publish</button>
</form>
