<a data-submit-form="publishForm-{{ $model->id }}" class="dropdown-link hover:bg-green-50 hover:text-green-500 cursor-pointer">Zet online</a>

<form class="hidden" id="publishForm-{{ $model->id }}" action="@adminRoute('publish', $model)" method="POST">
    {{ csrf_field() }}
    <button type="submit">Publish</button>
</form>
