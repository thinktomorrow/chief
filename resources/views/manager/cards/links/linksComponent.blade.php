<div data-links-component>
    <x-chief-card
        class="{{ isset($class) ? $class : '' }}"
        title="Permalink"
        :editRequestUrl="$manager->route('links-edit', $model)"
        type="links"
    >
        <div class="space-y-2">
            <div class="flex items-center space-x-2">
                <span class="font-medium text-success">

                </span>
            </div>

            <div class="space-y-1">
                @unless($linkForm->exist())
                    <p>Geen huidige links</p>
                @else
                    @foreach($linkForm->links() as $locale => $links)
                        @if($links->current)
                            <div class="flex justify-between items-center space-x-3">

                                <div>
                                    @if($links->is_online)
                                        <span class="inline-block mr-2 bg-green-300 cursor-pointer h-1 h-2 overflow-hidden rounded-full w-1 w-2" title="{{ $links->offline_reason }}"></span>
                                    @else
                                        <span class="inline-block mr-2 bg-red-300 cursor-help h-1 h-2 overflow-hidden rounded-full w-1 w-2" title="{{ $links->offline_reason }}"></span>
                                    @endif

                                    <a class="underline" target="_blank" rel="noopener" href="{{ $links->url }}">
                                        {{ $links->full_path }}
                                    </a>
                                </div>


                                <div>
                                    <span class="inline-block label bg-grey-100 text-grey-400 font-normal text-xs">{{ $locale }}</span>
                                </div>

                            </div>
                        @endif
                    @endforeach
                @endunless
            </div>
        </div>
    </x-chief-card>
</div>
