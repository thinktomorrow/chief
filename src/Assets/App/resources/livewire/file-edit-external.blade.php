@if ($isOpen)
    @php
        $ownerCount = count($previewFile->owners);
        $currentOwner = isset($modelReference) ? $previewFile->findOwner($modelReference) : null;
    @endphp

    <form class="space-y-4">
        <div class="flex gap-6 rounded-xl border border-grey-200 p-3 shadow-sm max-sm:flex-wrap sm:pr-6">
            <div
                class="flex h-64 w-full shrink-0 items-center justify-center overflow-hidden rounded-xl bg-grey-100 sm:h-48 sm:w-48"
            >
                @if ($previewFile->isPreviewable && $previewFile->previewUrl)
                    <img
                        src="{{ $previewFile->previewUrl }}"
                        alt="Preview image"
                        class="h-full w-full object-contain"
                    />
                @elseif ($previewFile->mimeType)
                    <x-dynamic-component
                        :component="MimetypeIcon::fromString($previewFile->mimeType)->icon()"
                        class="size-8 text-grey-400"
                    />
                @else
                    <x-chief::icon.attachment class="size-8 text-grey-400" />
                @endif
            </div>

            <div class="flex grow items-center">
                <div class="grow space-y-4">
                    @include('chief-assets::_partials.file-edit-preview-url')
                    @include('chief-assets::_partials.file-edit-external-metadata')
                </div>
            </div>
        </div>

        <div class="space-y-2">
            @include('chief-assets::_partials.file-edit-owner-info')

            <div class="flex flex-wrap gap-2">
                <button wire:click="openFilesChooseExternal" type="button">
                    <x-chief::button>
                        <svg><use xlink:href="#icon-replace"></use></svg>
                        Vervang extern bestand
                    </x-chief::button>
                </button>

                <button wire:click="updateExternalAsset" type="button">
                    <x-chief::button>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                            />
                        </svg>
                        Haal thumbnail opnieuw op van {{ ucfirst($previewFile->getData('external.type')) }}
                    </x-chief::button>
                </button>

                @include('chief-assets::_partials.file-edit-owner-action')
            </div>
        </div>

        @if (count($this->getComponents()) > 0)
            <div class="space-y-4">
                @foreach ($this->getComponents() as $component)
                    {{ $component }}
                @endforeach
            </div>
        @endif

        @if ($errors->any())
            <div class="space-y-2">
                @foreach ($errors->all() as $error)
                    <x-chief::inline-notification type="error">
                        {{ ucfirst($error) }}
                    </x-chief::inline-notification>
                @endforeach
            </div>
        @endif
    </form>

    <livewire:chief-wire::file-field-choose-external parent-id="{{ $this->getId() }}" />

    <x-slot name="footer">
        <x-chief::dialog.modal.footer>
            <x-chief-table::button wire:click.prevent="close" type="button">Annuleer</x-chief-table::button>
            <x-chief-table::button wire:click.prevent="submit" variant="primary" type="submit">
                Bewaar bestand
            </x-chief-table::button>
        </x-chief::dialog.modal.footer>
    </x-slot>
@endif
