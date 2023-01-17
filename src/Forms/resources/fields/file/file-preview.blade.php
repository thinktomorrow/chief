<div class="overflow-auto border divide-y rounded-lg border-grey-200 divide-grey-200 max-h-[24rem] shadow-sm">
    @foreach ($files ?? [] as $file)
        <div class="flex gap-4 p-2">
            <div class="shrink-0">
                <img
                    src="{{ $file->thumbUrl }}"
                    alt="..."
                    class="object-contain w-16 h-16 rounded-lg bg-grey-100"
                >
            </div>

            <div class="flex items-center py-2 grow">
                <div class="space-y-0.5 leading-tight">
                    <p class="text-black">
                        {{ $file->filename }}
                    </p>

                    <p class="text-sm text-grey-500">
                        {{ $file->size }} - {{ $file->mimeType }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 py-2 pr-2 shrink-0">
                <button type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                    <x-chief-icon-button icon="icon-edit" color="grey" />
                </button>

                <button type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                    <x-chief-icon-button icon="icon-chevron-up-down" color="grey" />
                </button>

                <button type="button" class="focus:ring-1 rounded-xl focus:ring-primary-500">
                    <x-chief-icon-button icon="icon-trash" color="grey" />
                </button>
            </div>
        </div>
    @endforeach
</div>
