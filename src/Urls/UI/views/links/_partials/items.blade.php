@php
    $redirects = $this->getRedirects()->groupBy(fn ($siteLink) => $siteLink->site->locale);
@endphp

@foreach ($this->links as $i => $link)
    <x-chief::callout :title="$link->site->name" wire:key="{{ $link->locale }}" variant="outline-white">
        <div
            class="space-y-3"
            x-data="{
                status: '{{ $link->status->value }}',
                baseUrl: '{{ $link->baseUrls[$link->locale] ?? '' }}',
                url: '{{ $link->url?->slugWithoutBaseUrlSegment }}',
            }"
        >
            <div class="flex items-start justify-between gap-2">
                <div class="mt-[0.1875rem]">
                    @if (! $this->allowedSite($link->locale))
                        <p class="mb-2 text-sm text-red-400">De pagina staat niet langer gepubliceerd op deze site.</p>
                    @endif

                    <p class="my-0.5 text-sm/5 font-medium text-grey-500">
                        <span x-text="baseUrl"></span>/<span x-text="url.replace(/^\/+/, '')"></span>
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    @if ($this->queuedForDeletion($link->locale))
                        <x-chief::button
                            x-on:click="$wire.undoDeleteSite('{{ $link->locale }}')"
                            variant="grey"
                            size="sm"
                        >
                            <x-chief::icon.arrow-turn-backward />
                            <span>Ongedaan maken</span>
                        </x-chief::button>
                    @else
                        {{-- No badge component here because of data binding that doesn't work with variant --}}
                        <span
                            x-text="status"
                            class="badge badge-sm"
                            x-bind:class="status == 'online' ? 'badge-green' : 'badge-grey'"
                        ></span>

                        <x-chief::button
                            x-on:click="$wire.deleteSite('{{ $link->locale }}')"
                            variant="outline-red"
                            size="sm"
                        >
                            <x-chief::icon.delete />
                        </x-chief::button>
                    @endif
                </div>
            </div>

            @if (! $this->queuedForDeletion($link->locale))
                <div class="space-y-4">
                    <x-chief::form.fieldset rule="form.{{$link->locale}}.slug">
                        <x-chief::form.label for="form.{{$link->locale}}.slug" required>Link</x-chief::form.label>
                        <x-chief::form.input.text
                            id="form.{{$link->locale}}.slug"
                            x-on:input="url = $event.target.value"
                            wire:model="form.{{ $link->locale }}.slug"
                        />
                    </x-chief::form.fieldset>

                    <x-chief::form.fieldset rule="form.{{$link->locale}}.status">
                        @if ($this->allowedSite($link->locale))
                            <x-chief::form.label for="form.{{$link->locale}}.status">Status</x-chief::form.label>

                            <x-chief::form.input.select
                                id="form.{{$link->locale}}.status"
                                wire:model="form.{{ $link->locale }}.status"
                                x-on:change="status = $event.target.value"
                            >
                                @foreach (\Thinktomorrow\Chief\Urls\Models\LinkStatus::options() as $optionValue => $optionLabel)
                                    <option wire:key="status-option-{{ $optionValue }}" value="{{ $optionValue }}">
                                        {{ $optionLabel }}
                                    </option>
                                @endforeach
                            </x-chief::form.input.select>
                        @endif
                    </x-chief::form.fieldset>

                    @php
                        $redirectsPerSiteCount = $redirects->get($link->locale) ? count($redirects->get($link->locale)) : 0;
                    @endphp

                    @if ($redirectsPerSiteCount > 0)
                        <x-chief::form.fieldset>
                            <x-chief::form.label>Redirects</x-chief::form.label>

                            <div
                                data-slot="control"
                                class="max-h-48 divide-y divide-grey-100 overflow-y-auto rounded-lg border border-grey-100"
                            >
                                @foreach ($redirects->get($link->locale) as $redirect)
                                    <div class="flex items-start justify-between gap-2 px-2 py-1">
                                        <div class="mt-[0.1875rem]">
                                            <p class="body-dark my-0.5 text-sm/5 font-medium">
                                                {{ $redirect->url->url }}
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-2">
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
                                @endforeach
                            </div>
                        </x-chief::form.fieldset>
                    @endif
                </div>
            @endif
        </div>
    </x-chief::callout>
@endforeach
