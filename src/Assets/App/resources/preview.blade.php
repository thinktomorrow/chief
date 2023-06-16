<div
    wire:sortable
    wire:start="$set('isReordering', true)"
    wire:end.stop="reorder($event.target.sortable.toArray())"
{{--    wire:sortable.options="{ animation: 100 }"--}}
    class="overflow-auto border divide-y rounded-lg border-grey-200 divide-grey-200 max-h-[24rem] shadow-sm">
    @foreach ($getFiles() as $file)

        @continue(count($getFiles()) > 1 && !$allowMultiple() && $file->isQueuedForDeletion)

       <div wire:sortable.item="{{ $file->id }}" class="flex gap-4 p-2">
            <div class="shrink-0">
                @if($file->isPreviewable)
                <img
                    src="{{ $file->previewUrl }}"
                    alt="..."
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
                        {{ $file->humanReadableSize }} - {{ $file->mimeType }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 py-2 pr-2 shrink-0">
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
                        <span class="text-xs text-primary-500" title="Je kan een bestand bewerken van zodra het is bewaard.">
                            Nog niet bewaard
                        </span>
                    @endif

                    <button wire:click="openFileEdit('{{ $file->id }}')" type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                        <x-chief::icon-button icon="icon-edit" color="grey" />
                    </button>

                    @if(count($getFiles()) > 1)
                        <button wire:sortable.handle type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                            <x-chief::icon-button icon="icon-chevron-up-down" color="grey" />
                        </button>
                    @endif

                    <button wire:click="deleteFile('{{ $file->id }}')" type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                        <x-chief::icon-button icon="icon-trash" color="grey" />
                    </button>
                @endif
            </div>
        </div>

    @endforeach

    @push('custom-scripts-after-vue')
        <script>
            @this.on('fileAdded', () => {
                console.log('file added.');
                // TODO: Tijs: scroll to bottom of this file container so the last added files are shown
                // document.getElementById('test').scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" });
            })
        </script>
    @endpush
</div>
