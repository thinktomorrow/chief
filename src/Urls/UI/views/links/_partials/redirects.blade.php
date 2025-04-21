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
