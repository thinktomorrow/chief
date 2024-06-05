@if($isOpen)
    @php
        $ownerCount = count($previewFile->owners);
        $currentOwner = isset($modelReference) ? $previewFile->findOwner($modelReference) : null;
    @endphp

    <form class="space-y-4">
        <div class="flex gap-6 p-3 border shadow-sm sm:pr-6 max-sm:flex-wrap rounded-xl border-grey-200">
            <div class="flex items-center justify-center w-full h-64 overflow-hidden sm:h-48 sm:w-48 bg-grey-100 rounded-xl shrink-0">
                @if($previewFile->isPreviewable)
                    <img
                        src="{{ $previewFile->previewUrl }}"
                        alt="Preview image"
                        class="object-contain w-full h-full"
                    >
                @else
                    <svg class="w-6 h-6 text-grey-400"><use xlink:href="#icon-paper-clip"/></svg>
                @endif
            </div>

            <div class="flex items-center grow">
                <div class="space-y-4 grow">
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
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        Haal thumbnail opnieuw op van {{ ucfirst($previewFile->getData('external.type')) }}
                    </x-chief::button>
                </button>

                @include('chief-assets::_partials.file-edit-owner-action')
            </div>
        </div>

        @if(count($this->getComponents()) > 0)
            <div class="space-y-4">
                @foreach($this->getComponents() as $component)
                    {{ $component }}
                @endforeach
            </div>
        @endif

        @if($errors->any())
            <div class="space-y-2">
                @foreach($errors->all() as $error)
                    <x-chief::inline-notification type="error">
                        {{ ucfirst($error) }}
                    </x-chief::inline-notification>
                @endforeach
            </div>
        @endif
    </form>

    <livewire:chief-wire::file-field-choose-external parent-id="{{ $this->getId() }}"/>

    <x-slot name="footer">
        <div class="flex flex-wrap justify-end gap-3">
            <button type="button" x-on:click="close()" class="btn btn-grey">
                Annuleer
            </button>

            <button wire:click.prevent="submit" type="submit" class="btn btn-primary">
                Bewaar bestand
            </button>
        </div>
    </x-slot>
@endif
