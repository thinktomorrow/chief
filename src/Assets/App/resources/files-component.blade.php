<div
    x-data="{
        uploadFiles: (files) => {
            files.forEach((file, index) => {
                @this.set('files.'+index+'.fileName', file.name );
                @this.set('files.'+index+'.fileSize', file.size );
                @this.set('files.'+index+'.progress', 0 );
                @this.upload('files.'+index+'.fileRef', file, (n)=>{}, ()=>{}, (e)=>{
                    // Progress callback
                    @this.set('files.'+index+'.progress', e.detail.progress);
                });
            });
        },
        isReordering: @entangle('isReordering'),
        onDragEnter: () => {
            if($data.isReordering) return;
            $el.classList.add('m-[-2px]', 'border-2', 'border-dashed', 'rounded-lg', 'border-primary-500');
            $data.hasEnteredDrag = true;
        },
        onDragLeave: () => {
            $el.classList.remove('m-[-2px]', 'border-2', 'border-dashed', 'rounded-lg', 'border-primary-500');
        },
        onDrop: (event) => {
            if($data.isReordering) return;
            const files = event.dataTransfer.files;
            $data.uploadFiles([...files]);
        }
    }"
     x-on:dragenter.prevent="onDragEnter"
     x-on:dragover.prevent="onDragEnter"
     x-on:dragleave.prevent="onDragLeave"
     x-on:drop.prevent="onDrop"
>
    {{ $this->filePreview }}

    @error('files.0')
        <x-chief::inline-notification type="error" class="mt-2">
            {{ ucfirst($message) }}
        </x-chief::inline-notification>
    @enderror

    {{ $this->fileSelect }}

    <livewire:chief-wire::files-choose parent-id="{{ $this->id }}" />
    <livewire:chief-wire::attached-file-edit
        parent-id="{{ $this->id }}"
        model-reference="{{ $modelReference }}"
        field-key="{{ $fieldKey }}"
        locale="{{ $locale }}"
        :components="$this->components"
    />

    @foreach(app(\Thinktomorrow\Chief\Plugins\ChiefPluginSections::class)->getLivewireFileComponents() as $livewireFileComponent)
        <livewire:is
            component="{{ $livewireFileComponent }}"
            parent-id="{{ $this->id }}"
            model-reference="{{ $modelReference }}"
            field-key="{{ $fieldKey }}"
            locale="{{ $locale }}"
        />
    @endforeach

{{--    <div><livewire:chief-wire::image-crop parent-id="{{ $this->id }}" /></div>--}}
</div>
