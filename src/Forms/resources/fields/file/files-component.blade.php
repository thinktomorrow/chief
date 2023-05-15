<div>
    {{ $this->filePreview }}

    @error('files.0')
        <span class="text-red-500">{{ $message }}</span>
    @enderror

    {{ $this->fileSelect }}
    <div><livewire:chief-wire::files-choose parent-id="{{ $this->id }}" /></div>
    <div><livewire:chief-wire::file-edit parent-id="{{ $this->id }}" :components="$this->components" /></div>
    <div><livewire:chief-wire::image-crop parent-id="{{ $this->id }}" /></div>
</div>
