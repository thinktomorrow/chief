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
        <div class="border-grey-200 flex gap-6 rounded-xl border p-3 shadow-xs max-sm:flex-wrap sm:pr-6">
            <div
                class="bg-grey-100 flex h-64 w-full shrink-0 items-center justify-center overflow-hidden rounded-xl sm:h-48 sm:w-48"
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
                        class="text-grey-400 size-8"
                    />
                @else
                    <x-chief::icon.attachment class="text-grey-400 size-8" />
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
                    <div>
                        <input wire:model="file" type="file" id="{{ $this->getId() }}" class="hidden" />
                        <x-chief::button for="{{ $this->getId() }}" variant="grey" size="sm">
                            <x-chief::icon.exchange />
                            <span>Vervang bestand</span>
                        </x-chief::button>
                    </div>

                    @if ($ownerCount > 1)
                        @include('chief-assets::_partials.file-edit-owner-action')
                    @endif

                    @foreach (app(ChiefPluginSections::class)->getLivewireFileEditActions() as $livewireFileEditAction)
                        @include($livewireFileEditAction)
                    @endforeach
                @endif
            </div>
        </div>

        <div>
            <x-chief::form.fieldset rule="form.basename">
                <x-chief::form.label for="form.basename">Bestandsnaam</x-chief::form.label>

                <x-chief::form.input.prepend-append :append="'.'.$previewFile->extension">
                    <x-chief::form.input.text
                        id="form.basename"
                        name="form[basename]"
                        placeholder="Bestandsnaam"
                        wire:model="form.basename"
                    />
                </x-chief::form.input.prepend-append>

                @if ($replacedPreviewFile)
                    <span class="text-grey-500 text-sm">
                        Vorige bestandsnaam was: {{ $replacedPreviewFile->filename }}
                    </span>
                @endif
            </x-chief::form.fieldset>

            <div data-slot="form-group">
                @include('chief-assets::_partials.file-edit-site-toggle')

                @foreach ($this->getComponents() as $component)
                    {{ $component }}
                @endforeach
            </div>

            @if ($errors->any())
                <x-chief::callout data-slot="form-group" size="small" variant="red">
                    @foreach ($errors->all() as $error)
                        <p>{{ ucfirst($error) }}</p>
                    @endforeach
                </x-chief::callout>
            @endif
        </div>
    </form>

    <x-slot name="footer">
        <x-chief::dialog.modal.footer>
            <x-chief::button type="button" x-on:click="close()">Annuleer</x-chief::button>
            <x-chief::button wire:click.prevent="submit" variant="blue" type="submit">Bewaar bestand</x-chief::button>
        </x-chief::dialog.modal.footer>
    </x-slot>
@endif
