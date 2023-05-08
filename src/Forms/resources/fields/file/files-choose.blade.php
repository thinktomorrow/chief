<div x-cloak x-data="{open:@entangle('isOpen')}" x-show="open" class="fixed inset-0 flex items-center justify-center z-[100]">
    @if($isOpen)
        <div class="absolute inset-0 bg-black/50"></div>

        <div class="relative p-12 bg-white rounded-xl">

            <button class="btn btn-primary-outline" type="button" x-on:click="open = false">X</button>

            <div class="overflow-auto border divide-y rounded-lg border-grey-200 divide-grey-200 max-h-[24rem] shadow-sm">
                @foreach ($getFiles() as $file)

                    <div class="flex gap-4 p-2">
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
                            <button wire:click="selectFile('{{ $file->id }}')" type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                                <x-chief-icon-button icon="icon-plus" color="grey" />
                            </button>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>
    @endif
</div>
