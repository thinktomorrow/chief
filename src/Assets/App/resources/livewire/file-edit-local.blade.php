@php
    use Thinktomorrow\Chief\Assets\App\MimetypeIcon;
    use Thinktomorrow\Chief\Plugins\ChiefPluginSections;
@endphp

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
                @if ($previewFile && $previewFile->isImage())
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
                    @include('chief-assets::_partials.file-edit-local-metadata')
                </div>
            </div>
        </div>

        <div class="space-y-2">
            @include('chief-assets::_partials.file-edit-owner-info')

            <div class="flex flex-wrap gap-2">
                @if ($previewFile)
                    {{-- Replace file action --}}
                    <label for="{{ $this->getId() }}" class="cursor-pointer">
                        <input wire:model="file" type="file" id="{{ $this->getId() }}" class="hidden" />

                        <x-chief::button>
                            <svg><use xlink:href="#icon-replace"></use></svg>
                            Vervang bestand
                        </x-chief::button>
                    </label>

                    @if ($ownerCount > 1)
                        @include('chief-assets::_partials.file-edit-owner-action')
                    @endif

                    @foreach (app(ChiefPluginSections::class)->getLivewireFileEditActions() as $livewireFileEditAction)
                        @include($livewireFileEditAction)
                    @endforeach
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <x-chief::input.group rule="form.basename">
                <x-chief::form.label for="form.basename">Bestandsnaam</x-chief::form.label>

                <x-chief::input.prepend-append :append="'.'.$previewFile->extension">
                    <x-chief::input.text
                        id="form.basename"
                        name="form[basename]"
                        placeholder="Bestandsnaam"
                        wire:model="form.basename"
                    />
                </x-chief::input.prepend-append>

                @if ($replacedPreviewFile)
                    <span class="text-sm text-grey-500">
                        Vorige bestandsnaam was: {{ $replacedPreviewFile->filename }}
                    </span>
                @endif
            </x-chief::input.group>

            @foreach ($this->getComponents() as $component)
                {{ $component }}
            @endforeach
        </div>

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

    <x-slot name="footer">
        <div class="flex flex-wrap justify-end gap-3">
            <button type="button" x-on:click="close()" class="btn btn-grey">Annuleer</button>

            <button wire:click.prevent="submit" type="submit" class="btn btn-primary">Bewaar bestand</button>
        </div>
    </x-slot>
@endif
