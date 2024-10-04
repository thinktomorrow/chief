<div wire:key="{{ $file->id }}" wire:sortable.item="{{ $file->id }}" class="@container relative">
    {{-- File upload progress bar --}}
    @if ($file->isUploading && isset($this->findUploadFile($file->id)['progress']) && $this->findUploadFile($file->id)['progress'] <= 100)
        <div class="absolute inset-0">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-green-100">
                <div
                    class="h-full bg-green-600 transition-all duration-300 ease-in-out"
                    style="width: {{ $this->findUploadFile($file->id)['progress'] }}%"
                ></div>
            </div>
        </div>
    @endif

    <div class="@md:flex-nowrap relative flex justify-between gap-x-4 gap-y-2 py-2 pl-2 pr-4 @1px:flex-wrap">
        <div class="flex gap-4">
            {{-- File thumb --}}
            <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-grey-100">
                @if ($file->isPreviewable && $file->previewUrl)
                    <img
                        src="{{ $file->previewUrl }}"
                        alt="{{ $file->filename }}"
                        class="h-full w-full object-contain"
                    />
                @else
                    <svg class="h-6 w-6 text-grey-400">
                        <use xlink:href="#icon-document" />
                    </svg>
                @endif
            </div>

            {{-- File information --}}
            <div class="grow space-y-0.5 break-all py-1.5 leading-tight">
                <p class="text-black">
                    {{ $file->filename }}

                    @if (! $file->isAttachedToModel)
                        <span class="label label-xs label-info">Sla op om te bewaren</span>
                    @endif
                </p>

                <p class="text-sm text-grey-500">
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
                    <a
                        href="{{ $file->getUrl() }}"
                        title="{{ $file->getUrl() }}"
                        target="_blank"
                        rel="noopener"
                        class="link link-primary"
                    >
                        <x-chief::icon-button icon="icon-external-link" />
                    </a>
                @endif

                <button
                    wire:click="openFileEdit('{{ $file->id }}')"
                    type="button"
                    class="rounded-xl focus:ring-1 focus:ring-primary-500"
                >
                    <x-chief::icon-button icon="icon-edit" color="grey" />
                </button>

                @if (count($files) > 1 && $allowMultiple())
                    <button wire:sortable.handle type="button" class="rounded-xl focus:ring-1 focus:ring-primary-500">
                        <x-chief::icon-button icon="icon-chevron-up-down" color="grey" />
                    </button>
                @endif

                <button
                    wire:click="deleteFile('{{ $file->id }}')"
                    type="button"
                    class="rounded-xl focus:ring-1 focus:ring-primary-500"
                >
                    <x-chief::icon-button icon="icon-trash" color="grey" />
                </button>
            </div>
        @endif

        @if (! $file->isValidated)
            <div
                class="@md:flex-nowrap absolute inset-0 flex items-center justify-between gap-2 bg-red-100/75 px-4 py-2 backdrop-blur-sm backdrop-filter @1px:flex-wrap"
            >
                <span class="body text-sm text-red-500">
                    {{ ucfirst($file->validationMessage) }}
                </span>

                <button type="button" wire:click="deleteFile('{{ $file->id }}')" class="ml-auto shrink-0">
                    <x-chief::button>
                        <svg>
                            <use xlink:href="#icon-x-mark"></use>
                        </svg>
                    </x-chief::button>
                </button>
            </div>
        @elseif ($file->isQueuedForDeletion)
            <div
                class="@md:flex-nowrap absolute inset-0 flex items-center justify-between gap-2 bg-white/75 px-4 py-2 backdrop-blur-sm backdrop-filter @1px:flex-wrap"
            >
                @if ($file->mediaId)
                    <span class="body body-dark text-sm">{{ $file->filename }} wordt verwijderd</span>
                @else
                    <span class="body body-dark text-sm">{{ $file->filename }} wordt niet bewaard</span>
                @endif

                <button type="button" wire:click="undoDeleteFile('{{ $file->id }}')" class="ml-auto shrink-0">
                    <x-chief::button>
                        Ongedaan maken
                        <svg>
                            <use xlink:href="#icon-undo"></use>
                        </svg>
                    </x-chief::button>
                </button>
            </div>
        @endif
    </div>
</div>
