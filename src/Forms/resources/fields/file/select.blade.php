<div>
    <div>
        <!-- Once the temp files are stored, this field is populated with the associated media record id -->
        @foreach($getFilesForUpload() as $i => $file)
            <div wire:key="files_for_upload_{{$file->id}}">
                <input type="hidden" name="{{ $getFieldName() }}[uploads][{{ $i }}][id]" value="{{$file->id}}" />
                <input type="hidden" name="{{ $getFieldName() }}[uploads][{{ $i }}][path]" value="{{$file->tempPath}}" />
                <input type="hidden" name="{{ $getFieldName() }}[uploads][{{ $i }}][originalName]" value="{{$file->filename}}" />
                <input type="hidden" name="{{ $getFieldName() }}[uploads][{{ $i }}][mimeType]" value="{{$file->mimeType}}" />
            </div>
        @endforeach

        @foreach($getFilesForAttach() as $i => $file)
            <input wire:key="files_for_attach_{{$file->id}}" type="hidden" name="{{ $getFieldName() }}[attach][{{ $i }}]" value="{{ $file->id }}">
        @endforeach

        @foreach($getFilesForDeletion() as $i => $file)
            <input wire:key="files_for_deletion_{{$file->id}}" type="hidden" name="{{ $getFieldName() }}[queued_for_deletion][{{ $i }}]" value="{{ $file->id }}">
        @endforeach

        @foreach($getFiles() as $i => $file)
            <input wire:key="files_for_order_{{$file->id}}" type="hidden" name="{{ $getFieldName() }}[order][{{ $i }}]" value="{{ $file->id }}">
        @endforeach
    </div>

    <div class="flex border border-dashed divide-x rounded-lg shadow-sm border-grey-200 divide-grey-200 divide-dashed">

        <label for="{{ $getFieldId() }}" class="relative w-1/2">

            <div x-data="{isUploading: false, isDone: false, progress: 0}"
                 x-show="isUploading"
                 x-on:livewire-upload-start="isUploading = true"
                 x-on:livewire-upload-finish="() => {}"
                 x-on:livewire-upload-error="isUploading = false"
                 x-on:livewire-upload-progress="progress = $event.detail.progress"
            >

                <input
                    type="file"
                    id="{{ $getFieldId() }}"
                    {{ $allowMultiple() ? 'multiple' : '' }}
                    x-on:change="() => {

                        const fileList = [...$el.files];

                        uploadFiles(fileList);

{{--                        fileList.forEach((file, index) => {--}}
{{--                            @this.set('files.'+index+'.fileName', file.name );--}}
{{--                            @this.set('files.'+index+'.fileSize', file.size );--}}
{{--                            @this.set('files.'+index+'.progress', 0 );--}}
{{--                            @this.upload('files.'+index+'.fileRef', file, (n)=>{}, ()=>{}, (e)=>{--}}
{{--                                // Progress callback--}}
{{--                                @this.set('files.'+index+'.progress', e.detail.progress);--}}
{{--                            });--}}
{{--                        });--}}
                   }"
                    class="absolute inset-0 w-full opacity-0 cursor-pointer pointer-events-auto peer"
                />

                <progress class="w-full" max="100" x-bind:value="progress"></progress>
            </div>

            <div class="flex items-center gap-4 p-4 rounded-l-lg group peer-focus:ring-1 peer-focus:ring-primary-500">
                <div class="flex items-center justify-center w-12 h-12 rounded-full shrink-0 group-hover:bg-primary-50 bg-grey-100">
                    <svg class="w-6 h-6 text-black transition-all duration-75 ease-in-out group-hover:text-primary-500 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /> </svg>
                </div>

                <div class="space-y-0.5 leading-tight">
                    <p class="text-black">
                        Upload een nieuw bestand
                    </p>

                    {{-- Allowed file types --}}
                    <p class="text-sm text-grey-500">
                        JPEG, PNG, GIF & SVG
                    </p>
                </div>
            </div>
        </label>

        <a wire:click="openFilesChoose" class="cursor-pointer flex items-center w-1/2 gap-4 p-4 rounded-r-lg group">
            <div class="flex items-center justify-center w-12 h-12 rounded-full shrink-0 group-hover:bg-primary-50 bg-grey-100">
                <svg class="w-5 h-5 text-black transition-all duration-75 ease-in-out group-hover:text-primary-500 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /> </svg>
            </div>

            <div class="space-y-0.5 leading-tight">
                <span class="text-black">
                    Kies een bestand uit de mediabibliotheek
                </span>
            </div>
        </a>
    </div>
</div>
