@php
    use Thinktomorrow\Chief\Assets\App\MimetypeIcon;
@endphp

<div wire:key="{{ $file->id }}" wire:sortable.item="{{ $file->id }}" class="@container relative bg-white">
    {{-- File upload progress bar --}}
    @if ($file->isUploading && isset($this->findUploadFile($file->id)['progress']) && $this->findUploadFile($file->id)['progress'] <= 100)
        <div class="absolute inset-0">
            <div class="absolute right-0 bottom-0 left-0 h-1 bg-green-100">
                <div
                    class="h-full bg-green-600 transition-all duration-300 ease-in-out"
                    style="width: {{ $this->findUploadFile($file->id)['progress'] }}%"
                ></div>
            </div>
        </div>
    @endif

    <div class="@md:flex-nowrap @min-[1px]:flex-wrap relative flex justify-between gap-x-4 gap-y-2 py-2 pr-4 pl-2">
        <div class="flex gap-4">
            {{-- File thumb --}}
            <div class="bg-grey-100 flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg">
                @if ($file->isPreviewable && $file->previewUrl)
                    <img
                        src="{{ $file->previewUrl }}"
                        alt="{{ $file->filename }}"
                        class="h-full w-full object-contain"
                    />
                @elseif ($file->mimeType)
                    <x-dynamic-component
                        :component="MimetypeIcon::fromString($file->mimeType)->icon()"
                        class="text-grey-400 size-6"
                    />
                @else
                    <x-chief::icon.attachment class="text-grey-400 size-6" />
                @endif
            </div>

            {{-- File information --}}
            <div class="grow space-y-0.5 py-1.5 leading-tight break-all">
                <p class="text-black">
                    {{ $file->filename }}

                    @if (! $file->isAttachedToModel)
                        <span class="label label-xs label-info">Sla op om te bewaren</span>
                    @endif
                </p>

                <p class="text-grey-500 text-sm">
                    @if ($file->isExternalAsset)
                        {{ ucfirst($file->getExternalAssetType()) }} -
                        @if ($file->hasData('external.duration'))
                            {{ $file->getData('external.duration') }} sec
                        @endif
                    @else
                        {{ $file->humanReadableSize }} -
                        @if ($file->isImage())
                            {{ $file->width }}x{{ $file->height }} -
                        @endif

                        {{ strtoupper($file->extension) }}
                    @endif
                </p>
            </div>
        </div>

        {{-- File actions --}}
        @if ($file->isValidated && ! $file->isQueuedForDeletion)
            <div class="ml-auto flex items-center gap-1.5">
                @if ($file->isExternalAsset)
                    <x-chief::button
                        href="{{ $file->getUrl() }}"
                        title="{{ $file->getUrl() }}"
                        target="_blank"
                        rel="noopener"
                        size="sm"
                    >
                        <x-chief::icon.link-square />
                    </x-chief::button>
                @endif

                @if (count($files) > 1 && $allowMultiple())
                    <x-chief::button wire:sortable.handle size="sm" variant="grey">
                        <x-chief::icon.drag-drop-arrows />
                    </x-chief::button>
                @endif

                <x-chief::button wire:click="deleteFile('{{ $file->id }}')" size="sm" variant="outline-red">
                    <x-chief::icon.delete />
                </x-chief::button>

                <x-chief::button wire:click="openFileEdit('{{ $file->id }}')" size="sm" variant="grey">
                    <x-chief::icon.quill-write />
                </x-chief::button>
            </div>
        @endif

        @if (! $file->isValidated)
            <div
                class="@md:flex-nowrap @min-[1px]:flex-wrap absolute inset-0 flex items-center justify-between gap-2 bg-red-100/75 px-4 py-2 backdrop-blur-xs backdrop-filter"
            >
                <span class="body text-sm text-red-500">
                    {{ ucfirst($file->validationMessage) }}
                </span>

                <x-chief::button wire:click="deleteFile('{{ $file->id }}')" size="sm" variant="grey">
                    <x-chief::icon.cancel />
                </x-chief::button>
            </div>
        @elseif ($file->isQueuedForDeletion)
            <div
                class="@md:flex-nowrap @min-[1px]:flex-wrap absolute inset-0 flex items-center justify-between gap-2 bg-white/75 px-4 py-2 backdrop-blur-xs backdrop-filter"
            >
                @if ($file->mediaId)
                    <span class="body body-dark text-sm">{{ $file->filename }} wordt verwijderd</span>
                @else
                    <span class="body body-dark text-sm">{{ $file->filename }} wordt niet bewaard</span>
                @endif

                <x-chief::button wire:click="undoDeleteFile('{{ $file->id }}')" size="sm" variant="grey">
                    <x-chief::icon.arrow-turn-backward />
                    <span>Ongedaan maken</span>
                </x-chief::button>
            </div>
        @endif
    </div>
</div>
