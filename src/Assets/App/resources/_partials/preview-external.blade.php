<div class="shrink-0">
    @if($file->isPreviewable)
        <img
            src="{{ $file->previewUrl }}"
            alt="{{ $file->filename }}"
            class="object-contain w-16 h-16 rounded-lg bg-grey-100"
        >
    @endif
</div>

<div class="flex items-center py-2 grow">
    <div class="space-y-0.5 leading-tight">
        <p class="text-black">
            {{ $file->filename }}
        </p>

        <p class="text-sm text-grey-500">
            <span class="label label-info text-xs">{{ $file->getExternalAssetType() }}</span>

            @if($file->isVideo())
                {{ $file->getData('external.duration') }} sec. - {{ $file->width }} x {{ $file->height }}
            @else
                {{ $file->humanReadableSize }} -
                @if($file->isImage())
                    {{ $file->width }} x {{ $file->height }}
                @else
                    <span class="uppercase">{{ $file->extension }}</span>
                @endif
            @endif
        </p>


        {{--                    @if(!$file->isValidated)--}}
        {{--                        <p class="text-sm text-red-400">{{ $file->validationMessage }}</p>--}}
        {{--                    @endif--}}
    </div>
</div>

<div class="flex items-center gap-2 py-2 pr-2 shrink-0">
    @if(!$file->isValidated)
        <span class="text-sm text-red-400">
                        {{ $file->validationMessage }}
                    </span>
        <button wire:click="deleteFile('{{ $file->id }}')" type="button" class="text-sm text-red-400">
            <svg width="18" height="18"><use xlink:href="#icon-x-mark"></use></svg>
        </button>
    @elseif($file->isUploading && isset($this->findUploadFile($file->id)['progress']) && $this->findUploadFile($file->id)['progress'] <= 100)
        {{--TODO: tijs make the progress pretty! maybe use a div but then we need to set the percentage via alpine--}}
        <div x-data="{progress: @entangle('files.'.$this->findUploadFileIndex($file->id).'.progress')}" class="w-full bg-grey-50 rounded h-2">
            <span x-text="progress"></span>%
        </div>
    @else
        @if($file->isQueuedForDeletion)
            @if($file->mediaId)
                <span class="text-sm text-primary-500">
                            Wordt verwijderd na bewaren. <span class="underline cursor-pointer link link-primary" wire:click="undoDeleteFile('{{ $file->id }}')">Ongedaan maken</span>
                        </span>
            @else
                <span class="text-sm text-primary-500">
                            <span class="underline cursor-pointer link link-primary" wire:click="undoDeleteFile('{{ $file->id }}')">Verwijderen ongedaan maken</span>
                        </span>
            @endif
        @else
            @if(!$file->isAttachedToModel)
                <span class="text-xs text-grey-500" title="Je kan een bestand bewerken van zodra het is bewaard.">
                            Nog niet bewaard
                        </span>
            @endif

            <a
                href="{{ $file->getUrl() }}"
                title="{{ $file->getUrl() }}"
                target="_blank"
                rel="noopener"
                class="link link-primary"
            >
                <x-chief::icon-button icon="icon-external-link" />
            </a>

            <button wire:click="openFileEdit('{{ $file->id }}')" type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                <x-chief::icon-button icon="icon-edit" color="grey" />
            </button>

            @if(count($files) > 1 && $allowMultiple())
                <button wire:sortable.handle type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                    <x-chief::icon-button icon="icon-chevron-up-down" color="grey" />
                </button>
            @endif

            <button wire:click="deleteFile('{{ $file->id }}')" type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                <x-chief::icon-button icon="icon-trash" color="grey" />
            </button>
        @endif
    @endif
</div>
