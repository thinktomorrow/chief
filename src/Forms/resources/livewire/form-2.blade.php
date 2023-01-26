<form class="w-full container"
      wire:submit.prevent="save"
      method="POST"
      enctype="multipart/form-data"
      role="form"
>
    @csrf

    <div class="relative space-y-6">

        formid: {{ $formId }}<br>
        titel is: {{ $model->title }}<br>

        <p>
            {{ $date }}
        </p>


        <button type="submit" class="relative btn btn-primary" style="z-index: 1;">
            Opslaan
        </button>
    </div>
</form>
