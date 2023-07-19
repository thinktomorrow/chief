<div class="sm:w-64 lg:w-80 flex flex-col gap-4 sm:gap-8 md:gap-4 sm:flex-row md:flex-col shrink-0">
    <div class="w-full overflow-hidden aspect-square bg-grey-100 rounded-xl flex justify-center items-center">
        @if($previewFile->isPreviewable)
            <img
                src="{{ $previewFile->previewUrl }}"
                class="object-contain w-full h-full"
            >
        @else
            <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-paper-clip" /></svg>
        @endif
    </div>

    <div class="space-y-4">
        <div class="flex flex-wrap gap-2">

            <label
                wire:click="openFilesChooseExternal"
                type="button"
                class="relative cursor-pointer"
            >
                <x-chief::icon-button icon="icon-replace"/>
            </label>

            <a
                href="{{ $previewFile->getUrl() }}"
                title="{{ $previewFile->getUrl() }}"
                target="_blank"
                rel="noopener"
                class="link link-primary"
            >
                <x-chief::icon-button icon="icon-external-link" />
            </a>

        </div>

        <div class="space-y-0.5 text-grey-500 text-sm">
            @if($previewFile->humanReadableSize)
                <dl class="flex justify-between">
                    <dt>Bestandsgrootte</dt>
                    <dd class="text-right">{{ $previewFile->humanReadableSize }}</dd>
                </dl>
            @endif

                @if($previewFile->isVideo())
                    <dl class="flex justify-between">
                        <dt>Lengte</dt>
                        <dd class="text-right">{{ $previewFile->getData('external.duration') }} secs.</dd>
                    </dl>
                @endif

            @if($previewFile->isImage() || $previewFile->isVideo())
                <dl class="flex justify-between">
                    <dt>Afmetingen</dt>
                    <dd class="text-right">{{ $previewFile->width }} x {{ $previewFile->height }}</dd>
                </dl>
            @endif

            @if($previewFile->extension)
                <dl class="flex justify-between">
                    <dt>Bestandsextensie</dt>
                    <dd class="text-right">{{ $previewFile->extension }}</dd>
                </dl>
            @endif

            @if($previewFile && $previewFile->createdAt)
                <dl class="flex justify-between">
                    <dt>Toegevoegd op</dt>
                    <dd class="text-right">{{ \Carbon\Carbon::createFromTimestamp($previewFile->createdAt)->format('d/m/Y H:i') }}</dd>
                </dl>

                @if($previewFile->updatedAt !== $previewFile->createdAt)
                    <dl class="flex justify-between">
                        <dt>Laatst aangepast</dt>
                        <dd class="text-right">{{ \Carbon\Carbon::createFromTimestamp($previewFile->updatedAt)->format('d/m/Y H:i') }}</dd>
                    </dl>
                @endif
            @endif
        </div>

        <div>
            <button
                wire:click="updateExternalAsset"
                type="button"
                class="relative flex gap-1.5 px-3 py-2 text-xs leading-5 rounded-full hover:bg-primary-50 bg-grey-100 body-dark hover:text-primary-500"
            >
                Haal metadata en thumbnail opnieuw op
            </button>
        </div>
    </div>
</div>

@if(count($this->getComponents()) > 0)

    <div class="space-y-6 grow">

            <div class="py-6 space-y-2 border-y border-grey-100">
                <h2 class="text-sm tracking-wider uppercase text-grey-500">Gegevens op deze pagina</h2>

                <div class="space-y-6">
                    @foreach($this->getComponents() as $component)
                        {{ $component }}
                    @endforeach
                </div>
            </div>

            <div class="space-y-2">
                @foreach($errors->all() as $error)
                    <x-chief::inline-notification type="error">
                        {{ ucfirst($error) }}
                    </x-chief::inline-notification>
                @endforeach
            </div>

            <div>
                <button wire:click.prevent="submit" type="submit" class="btn btn-primary">
                    Opslaan
                </button>
            </div>
    </div>
@endif

<div>
    <livewire:chief-wire::file-field-choose-external
        parent-id="{{ $this->id }}"
    />
</div>
