@php
    use Thinktomorrow\Chief\Plugins\ChiefPluginSections;
@endphp

@if($isOpen)
    @php
        $ownerCount = count($previewFile->owners);
        $currentOwner = isset($modelReference) ? $previewFile->findOwner($modelReference) : null;
    @endphp

    <form class="space-y-4">
        <div class="flex gap-6 p-3 border shadow-sm sm:pr-6 max-sm:flex-wrap rounded-xl border-grey-200">
            <div class="flex items-center justify-center w-full h-64 overflow-hidden sm:h-48 sm:w-48 bg-grey-100 rounded-xl shrink-0">
                @if($previewFile && $previewFile->isImage())
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
                    @include('chief-assets::_partials.file-edit-local-metadata')
                </div>
            </div>
        </div>

        <div class="space-y-2">
            @include('chief-assets::_partials.file-edit-owner-info')

            <div class="flex flex-wrap gap-2">
                @if($previewFile)
                    {{-- Replace file action --}}
                    <label for="{{ $this->getId() }}" class="cursor-pointer">
                        <input wire:model="file" type="file" id="{{ $this->getId() }}" class="hidden"/>

                        <x-chief::button>
                            <svg><use xlink:href="#icon-replace"></use></svg>
                            Vervang bestand
                        </x-chief::button>
                    </label>

                    @if($ownerCount > 1)
                        @include('chief-assets::_partials.file-edit-owner-action')
                    @endif

                    @foreach(app(ChiefPluginSections::class)->getLivewireFileEditActions() as $livewireFileEditAction)
                        @include($livewireFileEditAction)
                    @endforeach
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <x-chief::input.group rule="form.basename">
                <x-chief::input.label for="form.basename">Bestandsnaam</x-chief::input.label>

                <x-chief::input.prepend-append :append="'.'.$previewFile->extension">
                    <x-chief::input.text
                        id="form.basename"
                        name="form[basename]"
                        placeholder="Bestandsnaam"
                        wire:model="form.basename"
                    />
                </x-chief::input.prepend-append>

                @if($replacedPreviewFile)
                    <span class="text-sm text-grey-500">
                        Vorige bestandsnaam was: {{ $replacedPreviewFile->filename }}
                    </span>
                @endif
            </x-chief::input.group>

            @foreach($this->getComponents() as $component)
                {{ $component }}
            @endforeach
        </div>

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

    <x-slot name="footer">
        <div class="flex flex-wrap justify-end gap-3">
            <button type="button" x-on:click="open = false" class="btn btn-grey">
                Annuleer
            </button>

            <button wire:click.prevent="submit" type="submit" class="btn btn-primary">
                Bewaar bestand
            </button>
        </div>
    </x-slot>
@endif
