<x-chief::dialog wired>
    @if($isOpen)
        <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
        <form class="flex max-md:flex-col gap-8 w-full xs:w-96 sm:w-128 md:w-160 lg:w-192 max-h-[80vh] overflow-y-auto">
            <div class="sm:w-64 lg:w-80 flex flex-col gap-4 sm:gap-8 md:gap-4 sm:flex-row md:flex-col shrink-0">
                <div class="w-full overflow-hidden aspect-square bg-grey-100 rounded-xl flex justify-center items-center">
                    @if($previewFile->isImage())
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
                        {{-- Replace --}}
                        @if($previewFile)
                            <label for="{{ $this->id }}" class="relative cursor-pointer">
                                <x-chief::icon-button icon="icon-replace"/>
                                <input
                                    wire:model="file"
                                    type="file"
                                    id="{{ $this->id }}"
                                    class="absolute inset-0 opacity-0 w-8"
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
                                <dd class="text-right">{{ $previewFile->imageWidth }} x {{ $previewFile->imageHeight }}</dd>
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

                {{-- TODO(ben): add alt text field --}}

                @if(count($this->getComponents()) > 0)
                    <div class="py-6 space-y-2 border-y border-grey-100">
                        <h2 class="text-sm tracking-wider uppercase text-grey-500">Gegevens op deze pagina</h2>

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
        </form>
    @endif
</x-chief::dialog>
