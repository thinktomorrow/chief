<div class="space-y-4">
    @foreach ($this->sites as $i => $site)
        <x-chief::callout :title="$site->site->name" wire:key="{{ $site->locale }}" variant="outline-white">
            <div class="space-y-3"
                 x-data="{ status:'{{ $site->status->value }}', baseUrl: '{{ $site->baseUrls[$site->locale] ?? '' }}', url: '{{ $site->url?->slugWithoutBaseUrlSegment }}' }">
                <div class="flex items-start justify-between gap-2">
                    <div class="mt-[0.1875rem]">
                        <p class="my-0.5 text-sm/5 font-medium text-grey-500">
                            <span x-text="baseUrl"></span>/<span x-text="url"></span>
                        </p>
                    </div>

                    <div class="flex flex items-center gap-2">
                        @if ($this->queuedForDeletion($site->locale))
                            <x-chief::button
                                x-on:click="$wire.undoDeleteSite('{{ $site->locale }}')"
                                variant="grey"
                                size="sm"
                            >
                                <x-chief::icon.arrow-turn-backward />
                                <span>Ongedaan maken</span>
                            </x-chief::button>
                        @else
                            {{-- No badge component here because of data binding that doesn't work with variant --}}
                            <span x-text="status" class="badge badge-sm"
                                  x-bind:class="(status == 'online' ? 'badge-green' : 'badge-grey')"></span>

                            <x-chief::button
                                x-on:click="$wire.deleteSite('{{ $site->locale }}')"
                                variant="outline-red"
                                size="sm"
                            >
                                <x-chief::icon.delete />
                            </x-chief::button>
                        @endif
                    </div>

                </div>

                @if (! $this->queuedForDeletion($site->locale))
                    <div>
                        <div class="row-start-start gutter-2">
                            <div class="w-full sm:w-3/4">
                                <x-chief::form.fieldset rule="form.{{$site->locale}}.slug">
                                    <x-chief::form.label for="form.{{$site->locale}}.slug" required>Link
                                    </x-chief::form.label>
                                    <x-chief::form.input.text
                                        id="form.{{$site->locale}}.slug"
                                        x-on:input="url = $event.target.value"
                                        wire:model="form.{{ $site->locale }}.slug"
                                    />
                                </x-chief::form.fieldset>
                            </div>

                            <div class="w-full sm:w-1/4">
                                <x-chief::form.fieldset rule="form.{{$site->locale}}.status">
                                    <x-chief::form.label for="form.{{$site->locale}}.status">Status
                                    </x-chief::form.label>
                                    <x-chief::form.input.select
                                        id="form.{{$site->locale}}.status"
                                        wire:model="form.{{ $site->locale }}.status"
                                        x-on:change="status = $event.target.value"
                                    >
                                        @foreach (\Thinktomorrow\Chief\Urls\Models\LinkStatus::options() as $optionValue => $optionLabel)
                                            <option
                                                wire:key="status-option-{{ $optionValue }}"
                                                value="{{ $optionValue }}"
                                            >
                                                {{ $optionLabel }}
                                            </option>
                                        @endforeach
                                    </x-chief::form.input.select>
                                </x-chief::form.fieldset>
                            </div>

                            {{--                            @if (count($contexts) > 1)--}}
                            {{--                                <div class="w-full sm:w-1/2">--}}
                            {{--                                    <x-chief::form.fieldset rule="context">--}}
                            {{--                                        <x-chief::form.label for="context">Versie pagina opbouw</x-chief::form.label>--}}
                            {{--                                        <x-chief::form.input.select--}}
                            {{--                                            id="context"--}}
                            {{--                                            wire:model="form.{{ $site->locale }}.context"--}}
                            {{--                                        >--}}
                            {{--                                            @foreach ($contexts as $context)--}}
                            {{--                                                <option--}}
                            {{--                                                    wire:key="context-option-{{ $context->id }}"--}}
                            {{--                                                    value="{{ $context->id }}"--}}
                            {{--                                                >--}}
                            {{--                                                    {{ $context->title }}--}}
                            {{--                                                </option>--}}
                            {{--                                            @endforeach--}}
                            {{--                                        </x-chief::form.input.select>--}}
                            {{--                                    </x-chief::form.fieldset>--}}
                            {{--                                </div>--}}
                            {{--                            @endif--}}
                        </div>
                    </div>
                @endif
            </div>
        </x-chief::callout>
    @endforeach

    @foreach ($this->getRedirects()->groupBy(fn($siteLink) => $siteLink->site->locale) as $siteId => $redirectsPerSite)
        @if(count($redirectsPerSite) > 0)
            <x-chief::callout :title="'Redirects voor ' . $redirectsPerSite->first()->site->name"
                              wire:key="redirects-{{ $siteId }}"
                              variant="outline-white">
                @foreach($redirectsPerSite as $redirect)
                    <div class="space-y-3 border-t border-grey-100 p-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="mt-[0.1875rem]">
                                <p class="my-0.5 text-sm/5 font-medium text-grey-500">
                                    {{ $redirect->url->url }}
                                </p>
                            </div>

                            <div class="flex flex items-center gap-2">
                                @if ($this->redirectQueuedForDeletion($redirect->url->id))
                                    <x-chief::button
                                        x-on:click="$wire.undoDeleteRedirect('{{ $redirect->url->id }}')"
                                        variant="grey"
                                        size="sm"
                                    >
                                        <x-chief::icon.arrow-turn-backward />
                                        <span>Ongedaan maken</span>
                                    </x-chief::button>
                                @else
                                    <x-chief::button
                                        x-on:click="$wire.deleteRedirect('{{ $redirect->url->id }}')"
                                        variant="outline-red"
                                        size="sm"
                                    >
                                        <x-chief::icon.delete />
                                    </x-chief::button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </x-chief::callout>
        @endif
    @endforeach
</div>
