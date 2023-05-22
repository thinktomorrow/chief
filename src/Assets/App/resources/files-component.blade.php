<div x-data="{
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
        onDragEnter: () => { $el.classList.add('border-2','border-dashed'); },
        onDragLeave: () => { $el.classList.remove('border-2','border-dashed'); },
        onDrop: (event) => {
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
        <span class="text-red-500">{{ $message }}</span>
    @enderror

    {{ $this->fileSelect }}
    <div><livewire:chief-wire::files-choose parent-id="{{ $this->id }}" /></div>
    <div><livewire:chief-wire::file-edit parent-id="{{ $this->id }}" :components="$this->components" /></div>
    <div><livewire:chief-wire::image-crop parent-id="{{ $this->id }}" /></div>
</div>
