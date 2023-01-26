<div>
    <div wire:loading.delay.long>
        LOADING...
    </div>
    <div>

        <span>aantal keren form: {{ $count }}</span>

        @foreach($formData as $key => $value)
            <div wire:key="{{'preview-'.$key}}">
                <span>{{ $key }}: {{ print_r($value) }}</span>
            </div>
        @endforeach
    </div>
    <form class="w-full container" wire:submit.prevent="save"
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

        @if(isset($order))
            <input type="hidden" name="order" value="{{ $order ?? 0 }}">
        @endif

        <div class="relative space-y-6" wire:loading.class.delay.long="bg-orange-50">
            @foreach($this->form->getComponents() as $i => $childComponent)
                <div wire:key="form-components-{{ $i }}">
                    {{ $childComponent }}
                </div>
            @endforeach
            <button type="submit" class="relative btn btn-primary" style="z-index: 1;">
                Opslaan
            </button>
        </div>
    </form>
</div>

