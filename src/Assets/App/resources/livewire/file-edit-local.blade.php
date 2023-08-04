@if($isOpen)
    <form class="flex items-start gap-8 max-lg:flex-wrap">
        <div class="flex flex-col gap-4 sm:gap-8 lg:gap-4 sm:flex-row lg:flex-col shrink-0 w-full lg:w-[calc(30rem-4rem)]">
            <div class="flex items-center justify-center w-full overflow-hidden sm:w-2/5 lg:w-full aspect-square bg-grey-100 rounded-xl">
                @if($previewFile->isImage())
                    <img
                        src="{{ $previewFile->previewUrl }}"
                        alt="Preview image"
                        class="object-contain w-full h-full"
                    >
                @else
                    <svg width="24" height="24" class="text-grey-400"><use xlink:href="#icon-paper-clip" /></svg>
                @endif
            </div>

            <div class="w-full space-y-4 sm:w-3/5 lg:w-full">
                <div class="flex flex-wrap gap-2">
                    {{-- Replace --}}
                    @if($previewFile)
                        <label for="{{ $this->id }}" class="relative cursor-pointer">
                            <x-chief::icon-button icon="icon-replace"/>
                            <input
                                wire:model="file"
                                type="file"
                                id="{{ $this->id }}"
                                class="absolute inset-0 w-8 opacity-0"
                            />
                        </label>

                        {{-- Download --}}
                        @if($previewFile->getUrl())
                            <a href="{{ $previewFile->getUrl() }}" title="Download" class="shrink-0" download>
                                <x-chief::icon-button icon="icon-download"/>
                            </a>
                            <a
                                href="{{ $previewFile->getUrl() }}"
                                title="{{ $previewFile->getUrl() }}"
                                target="_blank"
                                rel="noopener"
                                class="link link-primary"
                            >
                                <x-chief::icon-button icon="icon-link" />
                            </a>
                        @endif

                        @foreach(app(\Thinktomorrow\Chief\Plugins\ChiefPluginSections::class)->getLivewireFileEditActions() as $livewireFileEditAction)
                            @include($livewireFileEditAction)
                        @endforeach

                    @endif
                </div>

                <div class="space-y-0.5 text-grey-500 text-sm">
                    <dl class="flex justify-between">
                        <dt>Bestandsgrootte</dt>
                        <dd class="text-right">{{ $previewFile->humanReadableSize }}</dd>
                    </dl>

                    @if($previewFile->isImage())
                        <dl class="flex justify-between">
                            <dt>Afmetingen</dt>
                            <dd class="text-right">{{ $previewFile->width }} x {{ $previewFile->height }}</dd>
                        </dl>
                    @endif

                    <dl class="flex justify-between">
                        <dt>Bestandsextensie</dt>
                        <dd class="text-right">{{ $previewFile->extension }}</dd>
                    </dl>

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
            </div>
        </div>

        <div class="space-y-6 grow">
            <x-chief::input.group rule="basename">
                <x-chief::input.label for="basename">Bestandsnaam</x-chief::input.label>

                <x-chief::input.prepend-append :append="'.'.$previewFile->extension">
                    <x-chief::input.text
                        id="basename"
                        name="basename"
                        placeholder="Bestandsnaam"
                        wire:model.lazy="form.basename"
                    />
                </x-chief::input.prepend-append>

                @if($replacedPreviewFile)
                    <span class="text-sm text-grey-500">Vorige bestandsnaam was: {{ $replacedPreviewFile->filename }}</span>
                @endif
            </x-chief::input.group>

            @if(count($this->getComponents()) > 0)
                <div class="py-6 space-y-2 border-y border-grey-100">
                    <h2 class="text-sm tracking-wider uppercase text-grey-500">Gegevens van de asset</h2>

                    <div class="space-y-6">
                        {{-- TODO(ben): add alt text field --}}
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
    </form>
@endif
