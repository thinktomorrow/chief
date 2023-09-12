@php use Thinktomorrow\Chief\Plugins\ChiefPluginSections; @endphp
@php use Carbon\Carbon; @endphp
@if($isOpen)
    <form class="flex items-start gap-8 max-lg:flex-wrap">
        <div
                class="flex flex-col gap-4 sm:gap-8 lg:gap-4 sm:flex-row lg:flex-col shrink-0 w-full lg:w-[calc(30rem-4rem)]">
            <div
                    class="flex items-center justify-center w-full overflow-hidden sm:w-2/5 lg:w-full aspect-square bg-grey-100 rounded-xl">
                @if($previewFile && $previewFile->isImage())
                    <img
                            src="{{ $previewFile->previewUrl }}"
                            alt="Preview image"
                            class="object-contain w-full h-full"
                    >
                @else
                    <svg width="24" height="24" class="text-grey-400">
                        <use xlink:href="#icon-paper-clip"/>
                    </svg>
                @endif
            </div>

            <div class="w-full space-y-4 sm:w-3/5 lg:w-full">
                <div class="flex flex-wrap gap-2">
                    {{-- Replace --}}
                    @if($previewFile)
                        <label for="{{ $this->getId() }}" class="cursor-pointer">
                            <input wire:model="file" type="file" id="{{ $this->getId() }}" class="hidden"/>

                            <x-chief::button>
                                <svg>
                                    <use xlink:href="#icon-replace"></use>
                                </svg>
                                Vervang bestand
                            </x-chief::button>
                        </label>

                        @foreach(app(ChiefPluginSections::class)->getLivewireFileEditActions() as $livewireFileEditAction)
                            @include($livewireFileEditAction)
                        @endforeach
                    @endif
                </div>

                <div class="flex items-start justify-between gap-2 space-y-2">
                    <a href="{{ $previewFile->getUrl() }}" target="_blank" title="{{ $previewFile->getUrl() }}"
                       class="mt-1.5">
                        <x-chief::link underline class="break-all">
                            {{ $previewFile->getUrl() }}
                        </x-chief::link>
                    </a>

                    <div class="flex gap-2 shrink-0">
                        <x-chief-assets::copy-url-button>
                            {{ $previewFile->getUrl() }}
                        </x-chief-assets::copy-url-button>

                        <a href="{{ $previewFile->getUrl() }}" title="{{ $previewFile->getUrl() }}" target="_blank"
                           rel="noopener">
                            <x-chief::link>
                                <svg>
                                    <use xlink:href="#icon-external-link"></use>
                                </svg>
                            </x-chief::link>
                        </a>
                    </div>
                </div>

                <div class="space-y-0.5 text-grey-500 text-sm">
                    <dl class="flex justify-between">
                        <dt>Bestandsgrootte</dt>
                        <dd class="text-right">{{ $previewFile->humanReadableSize }}</dd>
                    </dl>

                    @if($previewFile->isImage())
                        <dl class="flex justify-between">
                            <dt>Afmetingen</dt>
                            <dd class="text-right">{{ $previewFile->width }}x{{ $previewFile->height }}</dd>
                        </dl>
                    @endif

                    <dl class="flex justify-between">
                        <dt>Bestandsextensie</dt>
                        <dd class="text-right">{{ $previewFile->extension }}</dd>
                    </dl>

                    @if($previewFile && $previewFile->createdAt)
                        <dl class="flex justify-between">
                            <dt>Toegevoegd op</dt>
                            <dd class="text-right">{{ Carbon::createFromTimestamp($previewFile->createdAt)->format('d/m/Y H:i') }}</dd>
                        </dl>

                        @if($previewFile->updatedAt !== $previewFile->createdAt)
                            <dl class="flex justify-between">
                                <dt>Laatst aangepast</dt>
                                <dd class="text-right">{{ Carbon::createFromTimestamp($previewFile->updatedAt)->format('d/m/Y H:i') }}</dd>
                            </dl>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6 grow">
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
                    <span
                            class="text-sm text-grey-500">Vorige bestandsnaam was: {{ $replacedPreviewFile->filename }}</span>
                @endif
            </x-chief::input.group>

            @if(count($this->getComponents()) > 0)
                <div class="pt-6 space-y-2 border-t border-grey-100">
                    <h2 class="text-sm tracking-wider uppercase text-grey-500">Gegevens van de asset</h2>

                    <div class="space-y-6">
                        @foreach($this->getComponents() as $component)
                            {{ $component }}
                        @endforeach
                    </div>
                </div>
            @endif

            @if(count($previewFile->owners) > 0)

                <h3>In gebruik op:</h3>

                <p>Aanpassingen aan deze asset zullen zichtbaar zijn op alle pagina's. Wil je een aanpassing maken aan
                    deze asset enkel op deze pagina?</p>
                <span wire:click="detachAsset">Loskoppelen en afzonderlijk bewerken</span>

                <table>
                    @foreach($previewFile->owners as $owner)
                        <tr>
                            <td>{{ $owner->label }}</td>
                            <td><a target="_blank" href="{{ $owner->adminUrl }}">Bekijk</a></td>
                        </tr>
                    @endforeach
                </table>
            @endif

            @if($errors->any())
                <div class="pt-6 space-y-2 border-t border-grey-100">
                    @foreach($errors->all() as $error)
                        <x-chief::inline-notification type="error">
                            {{ ucfirst($error) }}
                        </x-chief::inline-notification>
                    @endforeach
                </div>
            @endif

            <x-slot name="footer">
                <button type="button" x-on:click="open = false" class="btn btn-grey">
                    Annuleren
                </button>

                <button wire:click.prevent="submit" type="button" class="btn btn-primary">
                    Opslaan
                </button>
            </x-slot>
        </div>
    </form>
@endif
