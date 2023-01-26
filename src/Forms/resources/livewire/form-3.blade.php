<form class="w-full container"
      method="POST"
      enctype="multipart/form-data"
      role="form"
>
    <div class="relative space-y-6">

      <p>{{ $count }}</p>

        <input type="text" wire:model="count">


        <button type="submit" class="relative btn btn-primary" style="z-index: 1;">
            Opslaan
        </button>
    </div>
</form>
