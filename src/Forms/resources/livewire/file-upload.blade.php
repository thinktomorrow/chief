{{ $this->filePreviews }}
{{ $this->fileSelection }}

{{--<div class="flex border border-dashed divide-x rounded-lg shadow-sm border-grey-200 divide-grey-200 divide-dashed">--}}
{{--    <label for="{{ $key }}" class="relative w-1/2">--}}
{{--        <input--}}
{{--            type="file"--}}
{{--            id="{{ $key }}"--}}
{{--            name="{{ $name }}[]"--}}
{{--            {{ $multiple ? 'multiple' : '' }}--}}
{{--            class="absolute inset-0 w-full opacity-0 cursor-pointer pointer-events-auto peer"--}}
{{--        />--}}

{{--        <div class="flex items-center gap-4 p-4 rounded-l-lg group peer-focus:ring-1 peer-focus:ring-primary-500">--}}
{{--            <div class="flex items-center justify-center w-12 h-12 rounded-full shrink-0 group-hover:bg-primary-50 bg-grey-100">--}}
{{--                <svg class="w-6 h-6 text-black transition-all duration-75 ease-in-out group-hover:text-primary-500 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /> </svg>--}}
{{--            </div>--}}

{{--            <div class="space-y-0.5 leading-tight">--}}
{{--                <p class="text-black">--}}
{{--                    Upload een nieuw bestand--}}
{{--                </p>--}}

{{--                --}}{{-- Allowed file types --}}
{{--                <p class="text-sm text-grey-500">--}}
{{--                    JPEG, PNG, GIF & SVG--}}
{{--                </p>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </label>--}}

{{--    <div class="flex items-center w-1/2 gap-4 p-4 rounded-r-lg group">--}}
{{--        <div class="flex items-center justify-center w-12 h-12 rounded-full shrink-0 group-hover:bg-primary-50 bg-grey-100">--}}
{{--            <svg class="w-5 h-5 text-black transition-all duration-75 ease-in-out group-hover:text-primary-500 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /> </svg>--}}
{{--        </div>--}}

{{--        <div class="space-y-0.5 leading-tight">--}}
{{--            <p class="text-black">--}}
{{--                Kies een bestand uit de mediabibliotheek--}}
{{--            </p>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}




{{--<div id="previews">--}}
{{--    @foreach($photos as $i => $photo)--}}
{{--        <div class="p-2 my-2 border-b">--}}
{{--            <h2>{{ $photo->getClientOriginalName() }}</h2>--}}

{{--            @if($photo->isPreviewable())--}}
{{--                <img class="w-24 h-24 m-2 border rounded" src="{{ $photo->temporaryUrl() }}" />--}}
{{--            @else--}}
{{--                <img class="w-24 h-24 m-2 border rounded" />--}}
{{--            @endif--}}

{{--            @error('photos.' . $i)--}}
{{--            <span class="text-red-500">{{ $message }}</span>--}}
{{--            @enderror--}}
{{--        </div>--}}


{{--    @endforeach--}}
{{--</div>--}}


{{--<div x-data="{isUploading: false, isDone: false, progress: 0}"--}}
{{--     x-on:livewire-upload-start="isUploading = true"--}}
{{--     x-on:livewire-upload-finish="() => alert('boe');"--}}
{{--     x-on:livewire-upload-error="isUploading = false"--}}
{{--     x-on:livewire-upload-progress="progress = $event.detail.progress"--}}
{{-->--}}

{{--    <input class="p-4 border" type="file" wire:model="photos" multiple>--}}

{{--    <div class="border bg-grey-400" x-show="isUploading">--}}
{{--        IS UPLOADEN...--}}
{{--    </div>--}}

{{--    <div class="border bg-grey-400" x-show="isUploading">--}}
{{--        <progress class="w-full" max="100" x-bind:value="progress"></progress>--}}
{{--    </div>--}}
{{--    <div class="border bg-grey-400" x-show="isDone">--}}
{{--        GEDAAN MET OPLADEN...--}}
{{--    </div>--}}

{{--</div>--}}

{{--@error('photos.*')--}}
{{--<span class="text-red-500">{{ $message }}</span>--}}
{{--@enderror--}}
