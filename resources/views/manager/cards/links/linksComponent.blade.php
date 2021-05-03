<div data-links-component>
    <x-chief-card
        class="{{ isset($class) ? $class : '' }}"
        title="Permalink"
        :editRequestUrl="$manager->route('links-edit', $model)"
        type="links"
    >
        <div class="space-y-2">
            @unless($linkForm->exist())
                <p class="text-grey-700">Geen huidige links</p>
            @else
                @foreach($linkForm->links() as $locale => $links)
                    @if($links->current)
                        <div class="flex items-start space-x-4">
                            <span class="flex-shrink-0 w-8 px-0 text-sm text-center label label-grey-light">{{ $locale }}</span>

                            <a class="mt-0.5 space-x-1 link link-primary" target="_blank" rel="noopener" href="{{ $links->url }}" style="word-break: break-word;">
                                <span>{{ $links->full_path }}</span>

                                @if($links->is_online)
                                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                                @else
                                    <span class="inline-block w-2 h-2 bg-red-500 rounded-full" title="{{ $links->offline_reason }}"></span>
                                @endif
                            </a>
                        </div>
                    @endif
                @endforeach
            @endunless
        </div>
    </x-chief-card>
</div>
