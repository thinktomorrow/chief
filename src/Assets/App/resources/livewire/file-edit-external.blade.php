@if($isOpen)
    @php
        $componentCount = count($this->getComponents());
    @endphp

    <form class="flex items-start gap-8 max-lg:flex-wrap">
        <div @class([
            'flex flex-col gap-4 shrink-0 w-full',
            'sm:flex-row lg:flex-col sm:gap-8 lg:gap-4 lg:w-[calc(30rem-4rem)]' => $componentCount > 0,
        ])>
            <div @class([
                'flex items-center justify-center w-full overflow-hidden aspect-square bg-grey-100 rounded-xl',
                'sm:w-2/5 lg:w-full' => $componentCount > 0,
            ])>
                @if($previewFile->isPreviewable)
                    <img
                        src="{{ $previewFile->previewUrl }}"
                        class="object-contain w-full h-full"
                    >
                @else
                    <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-paper-clip" /></svg>
                @endif
            </div>

            <div @class([
                'w-full space-y-4',
                'sm:w-3/5 lg:w-full' => $componentCount > 0,
            ])>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="openFilesChooseExternal" type="button">
                        <x-chief::button>
                            <svg><use xlink:href="#icon-replace"></use></svg>
                            Vervang link
                        </x-chief::button>
                    </button>
                </div>

                <div class="flex items-start justify-between gap-2 space-y-2">
                    <a href="{{ $previewFile->getUrl() }}" title="{{ $previewFile->getUrl() }}" class="mt-1.5">
                        <x-chief::link class="underline underline-offset-2">
                            {{ $previewFile->getUrl() }}
                        </x-chief::link>
                    </a>

                    <div class="flex gap-2 shrink-0">
                        <button
                            type="button"
                            x-data="{ showSuccessMessage: false }"
                            x-on:click="() => {
                                navigator.clipboard.writeText('{{ $previewFile->getUrl() }}');
                                showSuccessMessage = true;
                                setTimeout(() => showSuccessMessage = false, 2000);
                            }"
                        >
                            <x-chief::link>
                                <svg x-show="!showSuccessMessage"><use xlink:href="#icon-link"></use></svg>
                                <svg x-show="showSuccessMessage" class="text-green-500 animate-pop-in"><use xlink:href="#icon-check"></use></svg>
                            </x-chief::link>
                        </button>

                        <a href="{{ $previewFile->getUrl() }}" title="{{ $previewFile->getUrl() }}" target="_blank" rel="noopener">
                            <x-chief::link>
                                <svg><use xlink:href="#icon-external-link"></use></svg>
                            </x-chief::link>
                        </a>
                    </div>
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

                <button wire:click="updateExternalAsset" type="button">
                    <x-chief::button>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /> </svg>
                        Haal thumbnail opnieuw op van {{ ucfirst($previewFile->getData('external.type')) }}
                    </x-chief::button>
                </button>
            </div>
        </div>

        @if(count($this->getComponents()) > 0)
            <div class="space-y-6 grow">
                @if(count($this->getComponents()) > 0)
                    <div class="py-6 space-y-2 border-y border-grey-100">
                        <h2 class="text-sm tracking-wider uppercase text-grey-500">Gegevens van de asset</h2>

                        <div class="space-y-6">
                            @foreach($this->getComponents() as $component)
                                {{ $component }}
                            @endforeach
                        </div>
                    </div>
                @endif

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

        <livewire:chief-wire::file-field-choose-external parent-id="{{ $this->id }}" />
    </form>
@endif
