<div>
    <div wire:loading.delay.long>
        LOADING...
    </div>
    <form class="w-full container" wire:submit.prevent="submit"
          {{ $this->form->attributes->merge($this->form->getCustomAttributes()) }}
          id="{{ $this->form->getElementId() }}"
          method="POST"
          enctype="multipart/form-data"
          role="form"
          action="{{ $this->form->getAction() }}"
    >
        @csrf
        @if($this->form->getActionMethod() == 'PUT')
            @method('put')
        @endif

        {{ $this->form }}
    </form>
</div>

